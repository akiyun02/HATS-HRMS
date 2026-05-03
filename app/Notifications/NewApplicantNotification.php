<?php

namespace App\Notifications;

use App\Models\JobApplicant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewApplicantNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public JobApplicant $applicant)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'New Applicant',
            'message' => "{$this->applicant->first_name} applied for {$this->applicant->jobPosting->title}.",
            'url' => route('recruitment.index'),
            'icon' => 'user-plus'
        ];
    }
}

