<?php

namespace App\Notifications;

use App\Models\Admin\UserChange\UserChangeApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class UserChangeAppricationSubmitted extends Notification
{
    use Queueable;

    private UserChangeApplication $change_application;

    /**
     * Create a new notification instance.
     */
    public function __construct(UserChangeApplication $changeApplication)
    {
        $this->change_application = $changeApplication;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * 申請種別、申請の状態、再申請の親申請id、申請日、更新日、承認画面へのリンクを格納
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'action_type' => $this->change_application->action_type,
            'status' => $this->change_application->status,
            'parent_application_id' => $this->change_application->parent_application_id,
            'created_at' => $this->change_application->created_at,
            'updated_at' => $this->change_application->updated_at,
            'url' => route('admin.approvals.users.show', ['changeApplication' => $this->change_application]),
        ];
    }
}
