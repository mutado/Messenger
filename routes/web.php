<?php

use Illuminate\Support\Facades\Route;

use App\Message;

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

Route::get('/t', function () {
    // event(new \App\Events\MessageSent());
    broadcast(new \App\Events\MessageSent(Message::find(1)));
    dd('Event Run Successfully.');
});