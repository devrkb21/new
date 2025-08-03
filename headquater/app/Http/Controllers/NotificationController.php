<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Mark all unread notifications for the user as read.
     */
    // In app/Http/Controllers/NotificationController.php
    public function markAsRead()
    {
        // THE FIX: Use a standard query on our custom relationship
        Auth::user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }
}