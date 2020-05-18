<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
Route::get('status','ServerController@getStatus');

Route::group(['middleware' => 'auth:api'], function(){

    Route::post('details', 'API\UserController@details');
    Route::get('profile','API\UserController@getProfile');

    Route::get('user','API\UserController@getAll');
    Route::get('user/channels','API\ChannelController@getUserChannels');
    Route::post('user/update','API\UserController@update');

    Route::get('message','API\MessageController@index');
    Route::get('message/{id}','API\MessageController@getById');
    Route::post('message','API\MessageController@store');

    Route::get('channel','API\ChannelController@index');
    Route::get('channel/{id}/members','API\UserChannelsController@getMembers');
    Route::post('channel','API\ChannelController@store');
    Route::get('channel/{id}','API\ChannelController@getById');
    Route::get('channel/{channelId}/messages','API\ChannelController@getMessages');
    Route::get('channel/join/{id}','API\UserChannelsController@join');
    Route::get('channel/leave/{id}','API\UserChannelsController@leave');
    Route::get('channel/joined/{id}','API\ChannelController@joined');
    Route::post('channel/search','API\ChannelController@search');
    
});