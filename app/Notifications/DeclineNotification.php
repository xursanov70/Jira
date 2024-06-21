<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeclineNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private $message;
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
        ->subject("Vazifa rad qilindi!")
            ->line("Assalamu Alaykum. " . $this->message['hi'])
            ->line("task nomi: " . $this->message['task_name'])
            ->line("task haqida: " . $this->message['description'])
            ->line("categoriya nomi: " . $this->message['category_name'])
            ->line("task tugash vaqti: " . $this->message['original_task'])
            ->line("zarurlik darajasi: " . $this->message['high'])
            ->line("bekor qilinganlik sababi: " . $this->message['title'])
            ->line('Ilovamizdan foydalanganingiz uchun tashakkur!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
