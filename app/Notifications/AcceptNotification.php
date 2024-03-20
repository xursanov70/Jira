<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AcceptNotification extends Notification
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
        $url = 'https://instagram.com/jasko_70';
        return (new MailMessage)
            ->subject("Vazifa qabul bo'ldi")
            ->line("Assalamu Alaykum. Yuborilgan taskingiz partner tomonidan qabul qilindi")
            ->line("task nomi: " . $this->message['task_name'])
            ->line("task haqida: " . $this->message['description'])
            ->line("categoriya nomi: " . $this->message['category_name'])
            ->line("task tugash vaqti: " . $this->message['original_task'])
            ->line("zarurlik darajasi: " . $this->message['high'])
            ->line('Ilovamizdan foydalanganingiz uchun tashakkur!')
            ->action('Kirish', $url);
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
