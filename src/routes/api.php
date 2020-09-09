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

    $router->group(['prefix' => 'users'], function() use ($router) {
        // Get a list of users
        $router->get('/', ['uses' => 'UserController@listUsers']);
        // Create a new user
        $router->post('/', ['uses' => 'UserController@createUser']);
        // Delete an existing user
        $router->delete('/{id}', ['uses' => 'UserController@deleteUser']);
        // Update an existing user
        $router->put('/{id}', ['uses' => 'UserController@updateUser']);
    });
});
