<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class GenericNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $subject;
    protected $view;
    protected $data;
    protected $message;
    protected $from;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($subject, $view, $data = [],$message,$from)
    {
        $this->subject = $subject;
        $this->view = $view;
        $this->data = $data;
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
        if(app()->environment() == 'production'){
            return ['mail', 'database', 'whatsapp'];
        }
        return ['mail', 'database'];
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
            ->subject($this->subject)
            ->markdown($this->view, $this->data);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->data;
    }
    public function toWhatsapp($notifiable)
    {
        return [
            'message' => $this->message,
            'from' => $this->from,
        ];
    }
}
