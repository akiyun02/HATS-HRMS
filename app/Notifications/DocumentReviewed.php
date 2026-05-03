<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DocumentReviewed extends Notification
{
    use Queueable;

    public function __construct(public Document $document) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Your document '{$this->document->name}' was ".strtolower($this->document->status),
            'status' => $this->document->status,
            'url' => route('profile.show'),
        ];
    }
}
