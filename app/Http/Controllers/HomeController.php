<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
//        $user = $request->user();
//        $oauthUserToken = $user->userChannels()->where('name', 'discord_oauth_token')->first()->value;
//        $oauthUser = Socialite::driver('discord')->userFromToken($oauthUserToken);
        return view('home');
    }
}
