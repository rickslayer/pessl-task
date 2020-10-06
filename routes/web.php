<?php

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
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('alive', function () {
        return ['status' => true, 'message' => "I'm Alive"];
    });

    $router->get('payload',[
         'uses' => 'PayloadController@index',
         'as' => 'payload.index'
    ]);

    $router->post('user',[
        'uses' => 'UserController@add',
        'as' => 'user.add'
    ]);

    $router->get('user',[
        'uses' => 'UserController@get',
        'as' => 'user.get'
    ]);

});
