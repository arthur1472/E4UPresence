<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/profile', 'ProfileController@index')->name('profile');
Route::get('/connect/discord', 'ConnectController@discord')->name('connect.discord');
Route::get('/callback/discord', 'CallbackController@discord')->name('callback.discord');
Route::get('/callback/telegram', 'CallbackController@telegram')->name('callback.telegram');
Route::get('/test', function() {
    return view('test');
})->name('test');
