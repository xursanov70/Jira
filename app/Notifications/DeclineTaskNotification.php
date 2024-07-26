<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeclineTaskNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $taskMessage;
    public function __construct($taskMessage)
    {
        $this->taskMessage = $taskMessage;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Yuborilgan vazifa rad qilindi',
            'task_name' => $this->taskMessage->task_name,
            'description' => $this->taskMessage->description,
            'category_name' => $this->taskMessage->category_name,
            'original_task' => $this->taskMessage->original_task,
            'high' => $this->taskMessage->high,
        ];
    }
}
