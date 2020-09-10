<?php

namespace App\Jobs;

use App\DiscordChannel;
use App\Notifications\SendDiscordMessageNotification;
use App\Status;
use App\User;
use App\UserChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class GetE4uDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Http::get('http://paxton.trasmolenlaan5.everywhere4u.nl/Presence/GetPresence');
        $jsonResponse = $response->json();

        if (!isset($jsonResponse['Floors'])) {
            throw new \Exception('Can not find floors on the page.');
        }

        $presentContacts = [];

        $allowedFlooris = explode(',', env('ALLOWED_COMPANIES'));
        $floors = $jsonResponse['Floors'];

        foreach ($floors as $floor) {
            $companies = $floor['Companies'];
            foreach ($companies as $company) {
                if (in_array($company['Id'], $allowedFlooris)) {
                    foreach ($company['ContactPersonsPresent'] as $contactPerson) {
                        $presentContacts[] = $contactPerson['Id'];
                    }
                }
            }
        }

        $userChannel = UserChannel::where('name', 'e4u_id')->get();
        /** @var UserChannel $channel */
        $userChannel->each(function($channel) use (&$presentContacts) {
            if (in_array($channel->value, $presentContacts)) {
                $status = Status::find(2);
            } else {
                $status = Status::find(1);
            }
            /** @var User $user */
            $user = $channel->user;

            $user->status_id = $status->id;
            $user->save();

            if (array_search($channel->value, $presentContacts) !== false) {
                $presentContacts[array_search($channel->value, $presentContacts)] = null;
            }
        });

        /** @var DiscordChannel $discordChannel */
        $discordChannel = DiscordChannel::where('name', 'unknown-ids')->first();
        foreach ($presentContacts as $presentContact) {
            if (!$presentContact) {
                continue;
            }
            $discordChannel->notify(new SendDiscordMessageNotification('Unknown E4U id found, ID: '.$presentContact));
        }
    }
}
