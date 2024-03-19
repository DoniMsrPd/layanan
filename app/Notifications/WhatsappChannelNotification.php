<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WhatsappChannelNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $from;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message, $from, $data = [])
    {
        $this->message = $message;
        $this->from = $from;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'whatsapp'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', 'https://laravel.com')
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }


    public function toWhatsapp($notifiable)
    {
        return [
            'message' => $this->message,
            'from' => $this->from,
        ];
    }
}
