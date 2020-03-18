<?php

namespace App;

use App\Events\UserChangedEvent;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\Discord\Discord;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function userChannels() {
        return $this->hasMany(UserChannel::class);
    }

    public function status() {
        return $this->belongsTo(Status::class);
    }

    /**
     * Route notifications for the Telegram channel.
     *
     * @return int
     */
    public function routeNotificationForTelegram()
    {
        return $this->userChannels()->where('name', 'telegram_id')->first()->value;
    }

    /**
     * Route notifications for the Telegram channel.
     *
     * @return int
     * @throws \Exception
     */
    public function routeNotificationForDiscord()
    {
        $discordPrivateChannelId = $this->userChannels()->where('name', 'discord_private_channel_id')->first()->value ?? null;
        if ($discordPrivateChannelId) {
            return $discordPrivateChannelId;
        } else {
            $discordId = $this->userChannels()->where('name', 'discord_id')->first()->value ?? null;
            if (!$discordId) {
                throw new \Exception('Discord ID is not set. Can not send message.');
            }

            $discordPrivateChannelId = app(Discord::class)->getPrivateChannel($discordId);

            UserChannel::updateOrCreate(
                [
                    'user_id' => $this->id,
                    'name' => 'discord_private_channel_id'
                ],
                [
                    'value' => $discordPrivateChannelId
                ]
            );

            return $discordPrivateChannelId;
        }
    }

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'updated' => UserChangedEvent::class,
    ];
}
