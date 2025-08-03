<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// SCHEDULED TASKS FOR SUBSCRIPTIONS ⏰
// -----------------------------------------------------------------

// Generate invoices and send renewal reminders daily at 9:00 AM.
Schedule::command('subscriptions:send-reminders')->dailyAt('09:00');

// Check for expired grace periods and downgrade unpaid subscriptions daily at 9:05 AM.
Schedule::command('subscriptions:check-expirations')->dailyAt('09:05');