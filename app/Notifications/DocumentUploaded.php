<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DocumentUploaded extends Notification
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
            'message' => "New document uploaded: {$this->document->name}",
            'user_name' => $this->document->user->name,
            'document_id' => $this->document->id,
            'url' => route('employees.show', $this->document->user_id),
        ];
    }
}
