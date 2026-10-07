<?php

namespace App\Http\Controllers;

use App\Models\Admin\UserChange\UserChangeApplication;
use App\Models\User;
use App\Notifications\UserChangeApplicationApproved;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()
            ->unreadNotifications()
            ->get();

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

    // patchで呼び出すためのオリジナルの既読化メソッド
    public function markAsRead(Request $request)
    {
        $request->validate([
            'notification_ids' => ['required', 'array'],
            'notification_ids.*' => ['required', 'uuid'],
        ]);

        $request->user()
            ->unreadNotifications()
            ->whereIn('id', $request->notification_ids)
            ->update([
                'read_at' => now(),
            ]);

        return response()->json([
            'message' => '通知を既読にしました。',
        ]);
    }
}
