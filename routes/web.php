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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function() use ($router) {

    $router->group(['prefix' => 'users'], function() use ($router) {
        $router->get('/', ['uses' => 'UserController@listUsers']);
        // These two are untested
        $router->post('/', ['uses' => 'UserController@createUser']);
        $router->delete('/{id}', ['uses' => 'UserController@deleteUser']);
    });

});
