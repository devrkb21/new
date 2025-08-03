<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\SetLocale;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: 'api',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => IsAdmin::class,
            'phone.verified' => \App\Http\Middleware\EnsurePhoneIsVerified::class,

        ]);
        $middleware->web(append: [
            SetLocale::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule) { // <-- ADD THIS ENTIRE METHOD
        $schedule->command('subscriptions:check-expirations')->daily();
        $schedule->command('subscriptions:send-reminders')->daily();
        $schedule->command('subscriptions:apply-changes')->daily();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();