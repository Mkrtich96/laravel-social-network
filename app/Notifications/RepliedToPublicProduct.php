<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RepliedToPublicProduct extends Notification
{
    use Queueable;

    protected $product;

    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($product, $user)
    {
        $this->product = $product;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [CustomProduct::class];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'product_description' => $this->product->description,
            'product_price' => $this->product->price,
            'product_created_at' => parseCreatedAt($this->product->created_at),
            'user_name' => $this->user->name,
            'user_id' => $this->user->id,
        ];
    }
}
