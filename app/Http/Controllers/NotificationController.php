<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function markAsRead(DatabaseNotification $notification)
    {
        if (auth()->id() !== $notification->notifiable_id) {
            abort(403);
        }

        $notification->markAsRead();

        return back();
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back();
    }
}
