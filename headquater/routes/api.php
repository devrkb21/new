<?php

use App\Http\Controllers\Api\V1\ProxyController;
use App\Http\Controllers\Api\V1\SiteController;
use App\Http\Middleware\AuthenticateSite;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // This registration route is now public, allowing new sites to register.
    Route::post('/sites/register', [SiteController::class, 'register']);

    // All other routes are protected and require a valid, registered site and API key.
    Route::middleware(AuthenticateSite::class)->group(function () {
        Route::get('/sites/status', [SiteController::class, 'getStatus']);
        Route::post('/sites/settings', [SiteController::class, 'updateSettings']);
        Route::post('/sites/increment-usage', [SiteController::class, 'incrementUsage']);
        Route::post('/sites/decrement-usage', [SiteController::class, 'decrementUsage']);
        Route::post('/proxy/courier-check', [ProxyController::class, 'checkCourierSuccess']);
    });
});