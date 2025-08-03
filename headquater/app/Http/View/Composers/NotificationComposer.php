<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class NotificationComposer
{
    public function compose(View $view)
{
    if (Auth::check()) {
        $user = Auth::user();
        $notifications = $user->notifications()->latest()->take(10)->get();
        // THE FIX: Use a standard query on our custom relationship
        $unread_count = $user->notifications()->whereNull('read_at')->count();

        $view->with('notifications', $notifications)->with('unread_count', $unread_count);
    } else {
        $view->with('notifications', collect())->with('unread_count', 0);
    }
}
}