<?php

namespace App\Notifications;

use App\Models\PayrollRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PayrollProcessedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public PayrollRecord $payrollRecord)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Payroll Generated',
            'message' => "Your payroll for {$this->payrollRecord->month} {$this->payrollRecord->year} has been processed.",
            'url' => route('payroll.show', $this->payrollRecord->user_id),
            'icon' => 'currency-dollar'
        ];
    }
}

