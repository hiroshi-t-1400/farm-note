<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications;

        return response()->view('notifications/index', compact('notifications'));
    }

    public function read(DatabaseNotification $notification)
    {
        abort_unless(
            $notification->notifiable_type === User::class
                && $notification->notifiable_id === Auth::id(),
            403
        );

        $notification->markAsRead();

        return response()->json([
            'message' => '通知を既読にしました。',
        ]);
    }
}
