<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where all the routes relating to the API are stored
|
*/

// Removed temporarily so error messages are shown correctly in browser
//$router->group(['prefix' => 'api', 'middleware' => 'jsonheader'], function() use ($router) {
use Illuminate\Support\Facades\DB;

$router->group(['prefix' => 'api'], function() use ($router) {
    $router->get('/', function() use ($router) {
        // Get the number of teams that haven't retired from the league yet
        $teams = DB::table('teams')
            ->whereNull('retired_on')
            ->get()
            ->count();

        return response()->json(
            [
                'league' => LEAGUE_NAME,
                'active_teams' => $teams
            ], 200);
    });

    /*
    |--------------------------------------------------------------------------
    | Authentication Routes
    |--------------------------------------------------------------------------
    |
    | Routes to do with authentication e.g. logging in, logging out and
    | identifying users.
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
    | User management routes. Creating, deleting and updating. All the user
    | routes require the user to have a valid token.
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

    /*
    |--------------------------------------------------------------------------
    | Season Routes
    |--------------------------------------------------------------------------
    |
    | Season management routes. Seasons are created automatically and cannot be
    | deleted. If a season has no games played it will not be show (hence why
    | there is no delete function)
    |
    */
    $router->group(['prefix' => 'seasons'], function() use ($router) {
        // Get all the seasons registered in this league
        $router->get('/', ['as' => 'season-list', 'uses' => 'SeasonController@listSeasons']);
        // Get information on a specific season
        $router->get('/{id}', ['as' => 'season-detail', 'uses' => 'SeasonController@getSeason']);
        // Get the teams playing in the season
        $router->get('/{id}/teams', ['as' => 'season-teams', 'uses' => 'SeasonController@getTeams']);
    });


    /*
    |--------------------------------------------------------------------------
    | Team Routes
    |--------------------------------------------------------------------------
    |
    | Team management routes including inspecting team information, viewing
    | team stats, creating new teams and 'retiring' teams. Teams cannot be
    | deleted due to them being too integral to database consistency. Therefore
    | a retired team is one that no longer appears in new seasons but still
    | appears in previous seasons.
    |
    */
    $router->group(['prefix' => 'teams', 'middleware' => 'auth'], function() use ($router) {
        // Create a new team
        $router->post('/', ['as' => 'team-create', 'uses' => 'TeamController@createTeam']);
        // Retire a team
        $router->post('/{id}/retire', ['as' => 'team-retire', 'uses' => 'TeamContoller@retireTeam']);
//        // Update a teams information
//        $router->put('/{id}', ['as' => 'team-update', 'uses' => 'TeamController@retireTeam']);
    });
    $router->group(['prefix' => 'teams'], function() use ($router) {
        $router->get('/', ['as' => 'team-list', 'uses' => 'TeamController@listTeams']);
        $router->get('/{id}', ['as' => 'team-detail', 'uses' => 'TeamController@getTeam']);
    });

});
