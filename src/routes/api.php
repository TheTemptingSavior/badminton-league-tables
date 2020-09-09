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

    /*
    |--------------------------------------------------------------------------
    | Authentication Routes
    |--------------------------------------------------------------------------
    |
    | Routes to do with authentication e.g. logging in, logging out and
    | identifying users
    |
    */
    $router->group(['prefix' => 'auth'], function() use ($router) {
        $router->post('/login', ['as' => 'login', 'uses' => 'AuthController@login']);
        $router->get('/logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
    });
    $router->group(['prefix' => 'auth', 'middleware' => 'auth'], function() use ($router) {
        // This route under auth required the 'auth' middleware so that the user can be
        // identified from their token
        $router->get('/me', ['as' => 'me', 'uses' => 'AuthController@me']);
    });

    /*
    |--------------------------------------------------------------------------
    | User Routes
    |--------------------------------------------------------------------------
    |
    | User management routes. Creating, deleting and updating
    |
    */
    $router->group(['prefix' => 'users', 'middleware' => 'auth'], function() use ($router) {
        // Get a list of users
        $router->get('/', ['as' => 'user-list', 'uses' => 'UserController@listUsers']);
        // Get a specific user
        $router->get('/{id}', ['as' => 'user-detail', 'uses' => 'UserController@getUser']);
        // Create a new user
        $router->post('/', ['as' => 'user-create', 'uses' => 'UserController@createUser']);
        // Delete an existing user
        $router->delete('/{id}', ['as' => 'user-delete', 'uses' => 'UserController@deleteUser']);
        // Update an existing user
        $router->put('/{id}', ['as' => 'user-update', 'uses' => 'UserController@updateUser']);
    });
});
