<?php

namespace App\Providers;

use Illuminate\Support\Facades\View; // <-- ADD THIS LINE
use App\Http\View\Composers\NotificationComposer; // <-- AND THIS LINE
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use the NotificationComposer for the client layout component
        View::composer('components.client-layout', NotificationComposer::class);
    }
}