<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CustomChannel
{

    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toDatabase($notifiable);

        return $notifiable->routeNotificationFor('database')->create([
            'id' => $notification->id,
            'type' => get_class($notification),
            'to'    =>  get_auth('id'),
            'data' => $data,
            'read_at' => null,
        ]);
    }

}
