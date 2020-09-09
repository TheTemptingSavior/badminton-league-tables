<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where all the routes relating to the API are stored
|
*/

$router->group(['prefix' => 'api'], function() use ($router) {
    $router->get('/', function() use ($router) {
        return Array("league" => LEAGUE_NAME);
    });

    $router->group(['prefix' => 'auth'], function() use ($router) {
        $router->post('/login', ['as' => 'login', 'uses' => 'AuthController@login']);
        $router->get('/logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
    });

    $router->group(['prefix' => 'auth'], function() use ($router) {
        $router->get('/me', ['as' => 'me', 'uses' => 'AuthController@me']);
    });

    $router->group(['prefix' => 'users', 'middleware' => 'auth'], function() use ($router) {
        // Get a list of users
        $router->get('/', ['as' => 'user-list', 'uses' => 'UserController@listUsers']);
        // Create a new user
        $router->post('/', ['as' => 'user-create', 'uses' => 'UserController@createUser']);
        // Delete an existing user
        $router->delete('/{id}', ['as' => 'user-detail', 'uses' => 'UserController@deleteUser']);
        // Update an existing user
        $router->put('/{id}', ['as' => 'user-delete', 'uses' => 'UserController@updateUser']);
    });
});
