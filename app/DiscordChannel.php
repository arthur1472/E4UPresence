<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class DiscordChannel extends Model
{
    use Notifiable;

    public function routeNotificationForDiscord()
    {
        return $this->channel_id;
    }
}
