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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('/users/login', 'UserLoginController@login');
Route::post('/users/register', 'UserRegistrationController@register');
Route::post('/users/events', 'EventsController@createEvent');
Route::get('/users/events', 'EventsController@getEventsList');
Route::get('/users/event', 'EventsController@showEvent');
Route::post('/users/register/fcm-device-token', 'FCMTokenController@updateDeviceToken');
Route::get('/users/register/send-message/{message}', 'FCMTokenController@sendMessage');
Route::get('/users/events/broadcast', 'EventsController@eventsBroadcast');
Route::get('/users/events/delete', 'EventsController@delete');
Route::post('/users/events/update', 'EventsController@updateEvent');


Route::get('date', function(){

            ///$startDate = time();
            $startDate = strtotime('2018-07-17 20:20:00');
            return date('Y-m-d H:i:s', strtotime('+1 day', $startDate));

});