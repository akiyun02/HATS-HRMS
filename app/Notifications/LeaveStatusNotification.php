<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class LeaveStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public LeaveRequest $leaveRequest)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Leave Status Updated',
            'message' => "Your {$this->leaveRequest->leaveType->name} request was {$this->leaveRequest->status}.",
            'url' => route('leaves.index'),
            'icon' => $this->leaveRequest->status === 'Approved' ? 'check-circle' : 'x-circle'
        ];
    }
}
