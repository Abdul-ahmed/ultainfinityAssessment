<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return redirect('/api');
});

$router->post('api/webhook', 'TelegramController@webhook');
$router->group(['prefix' => 'api', 'middleware' => 'auth.header'], function () use ($router) {
    $router->get('/', 'TelegramController@index');
    $router->post('login', ['as' => 'login', 'uses' => 'TelegramController@login']);
    $router->get('login-channel', ['as' => 'login.channel', 'uses' => 'TelegramController@channelLogin']);
    $router->post('send-message', 'TelegramController@sendMessage');
});


$router->get('/api/auth/response', function () use ($router) {
    return response()->json([
        'status' => 'error',
        'code' => 400,
        'message' => "user-id required on headers",
    ], 400);
});

$router->post('/api/auth/response', function () use ($router) {
    return response()->json([
        'status' => 'error',
        'code' => 400,
        'message' => "user-id required on headers",
    ], 400);
});

// $router->get('getme', 'TelegramController@testing');
// $router->get('botmanager', 'TelegramController@usingBotManager');
