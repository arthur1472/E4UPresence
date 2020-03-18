<?php

namespace App\Http\Controllers;

use App\UserChannel;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class CallbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function discord(Request $request) {
        try {
            $user = Socialite::driver('discord')->user();
        } catch (\Exception $e) {
            return redirect()->route('home');
        }
//        $accessTokenResponseBody = $user->accessTokenResponseBody;
        $token = $user->token;
        UserChannel::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'name' => 'discord_oauth_token'
            ],
            [
                'value' => $token
            ]
        );

        return redirect()->route('home');
    }

    public function telegram(Request $request) {
        $discordBotToken = config('services.telegram.token');
        $authData = $request->all();
        $checkHash = $authData['hash'];
        $dataCheckArray = [];

        unset($authData['hash']);

        foreach ($authData as $key => $value) {
            $dataCheckArray[] = $key . '=' . $value;
        }

        sort($dataCheckArray);
        $dataCheckString = implode("\n", $dataCheckArray);

        $secretKey = hash('sha256', $discordBotToken, true);
        $hash = hash_hmac('sha256', $dataCheckString, $secretKey);

        if (strcmp($hash, $checkHash) !== 0) {
            throw new \Exception('Data is NOT from Telegram');
        }

        if ((time() - $authData['auth_date']) > 86400) {
            throw new \Exception('Data is outdated');
        }

        UserChannel::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'name' => 'telegram_id'
            ],
            [
                'value' => $authData['id']
            ]
        );
        UserChannel::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'name' => 'telegram_name'
            ],
            [
                'value' => $authData['first_name']
            ]
        );

        return redirect()->route('home');
    }
}
