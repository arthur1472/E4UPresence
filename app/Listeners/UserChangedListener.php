<?php

namespace App\Listeners;

use App\Events\UserChangedEvent;
use App\Notifications\SendDiscordMessageNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\DiscordChannel;

class UserChangedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param UserChangedEvent $event
     * @return void
     */
    public function handle(UserChangedEvent $event)
    {
        /** @var User $user */
        $user = $event->user;

        if (!$user->wasChanged()) {
            return;
        }

        /** @var DiscordChannel $discordChannel */
        $discordChannel = DiscordChannel::where('name', 'precense-updates')->first();

        $changes = $user->getChanges();
        if (isset($changes['status_id'])) {
            $userStatus = $user->status;
            $time = Carbon::now()->timezone('Europe/Amsterdam')->toDateTimeString();
            $discordChannel->notify(new SendDiscordMessageNotification("[{$time}] {$user->name} is nu {$userStatus->display_name}"));
        }
    }
}
