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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/', 'TelegramController@index');
    $router->post('login', ['as' => 'login', 'uses' => 'TelegramController@login']);
    $router->get('login-channel', ['as' => 'login.channel', 'uses' => 'TelegramController@channelLogin']);

    $router->post('send-message', 'TelegramController@sendMessage');
    $router->post('webhook', 'TelegramController@webhook');
});

// $router->get('getme', 'TelegramController@testing');
// $router->get('botmanager', 'TelegramController@usingBotManager');
