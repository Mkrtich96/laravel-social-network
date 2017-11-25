<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class CustomChannel
{

    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toDatabase($notifiable);

        return $notifiable->routeNotificationFor('database')->create([
            'id' => $notification->id,
            'type' => get_class($notification),
            'to'    =>  get_auth('id'),
            'system' => 'follow',
            'data' => $data,
            'read_at' => null,
        ]);
    }

}
