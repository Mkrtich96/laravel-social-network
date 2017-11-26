<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ToMail extends Notification
{
    use Queueable;

    protected $status;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($status = false)
    {
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if($this->status === true){
            return (new MailMessage)
                ->line('Your product has been approved from administrators.')
                ->action('For see your product.', url('/products'))
                ->line('Thank you for using our application!');
        }else{
            return (new MailMessage)
                ->line('Your product has been denied from administrators.')
                ->action('Please try again.', url('/products'))
                ->line('Thank you for using our application!');
        }

    }
}
