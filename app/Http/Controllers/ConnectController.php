<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class ConnectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function discord() {
        return Socialite::with('discord')->redirect();
    }
}
