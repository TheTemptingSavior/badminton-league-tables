<?php

use Illuminate\Support\Facades\DB;

// Removed temporarily so error messages are shown correctly in browser
$router->group(['prefix' => 'api', 'middleware' => ['jsonheader', 'cors']], function() use ($router) {
    $router->options('/{any:.*}', ['as' => 'cors-options', function() use ($router) {
       return response()->json('', 200);
    },]);

    $router->get('/', function() use ($router) {
        // Get the number of teams that haven't retired from the league yet
        $teams = DB::table('teams')
            ->get()
            ->count();

        return response()->json(
            [
                'league' => LEAGUE_NAME,
                'teams' => $teams,
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
        $router->get('/refresh', ['as' => 'refresh', 'uses' => 'AuthController@refresh']);
    });

    /*
    |--------------------------------------------------------------------------
    | Registrar Routes
    |--------------------------------------------------------------------------
    |
    | Routes to do with registering new users that will receive updates for 
    | events happening in this application
    |
    */
    $router->group(['prefix' => 'registrar'], function() use ($router) {
        $router->post('/', ['as' => 'registrar-create', 'uses' => 'RegistrarController@createRegistrar']);
    });
    $router->group(['prefix' => 'registrar', 'middleware' => ['auth']], function() use ($router) {
        $router->get('/', ['as' => 'registrar-list', 'uses' => 'RegistrarController@listRegistrar']);
    });
    $router->group(['prefix' => 'registrar'], function() use ($router) {
        $router->get('/', ['as' => 'registrar-delete', 'uses' => 'RegistrarController@deleteRegistrar']);
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
    $router->group(['prefix' => 'users', 'middleware' => ['auth', 'admin']], function() use ($router) {
        // Get a list of users
        $router->get('/', ['as' => 'user-list', 'uses' => 'UserController@listUsers']);
        // Get a specific user
        $router->get('/{id}', ['as' => 'user-detail', 'uses' => 'UserController@getUser']);
        // Create a new user
        $router->post('/', ['as' => 'user-create', 'uses' => 'UserController@createUser']);
        // Delete an existing user
        $router->delete('/{id}', ['as' => 'user-delete', 'uses' => 'UserController@deleteUser', 'middleware' => 'admin']);
        // Update a users admin status
        $router->put('/{id}/admin', ['as' => 'user-admin', 'uses' => 'UserController@makeAdmin', 'middleware' => 'admin']);
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
        //  Get information on the current season
        $router->get('/current', ['as' => 'season-current', 'uses' => 'SeasonController@currentSeason']);
        // Get information on a specific season
        $router->get('/{id}', ['as' => 'season-detail', 'uses' => 'SeasonController@getSeason']);
        // Get a list of all the games in the season
        $router->get('/{id}/scorecards', ['as' => 'season-scorecards', 'uses' => 'SeasonController@getScorecards']);
        // Get the teams playing in the season
        $router->get('/{id}/teams', ['as' => 'season-teams', 'uses' => 'SeasonController@getTeams']);
        // Get a season by its slug
        $router->get('/fromslug/{slug}', ['as' => 'season-slug', 'uses' => 'SeasonController@getFromSlug']);
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
    $router->group(['prefix' => 'teams', 'middleware' => ['auth', 'admin']], function() use ($router) {
        // Create a new team
        $router->post('/', ['as' => 'team-create', 'uses' => 'TeamController@createTeam']);
        // Retire a team
        $router->put('/{id}/retire', ['as' => 'team-retire', 'uses' => 'TeamController@retireTeam']);
        // Update a teams information
        $router->put('/{id}', ['as' => 'team-update', 'uses' => 'TeamController@updateTeam']);
    });
    $router->group(['prefix' => 'teams'], function() use ($router) {
        // List the teams in the league
        $router->get('/', ['as' => 'team-list', 'uses' => 'TeamController@listTeams']);
        // Get a specific team
        $router->get('/{id}', ['as' => 'team-detail', 'uses' => 'TeamController@getTeam']);
        // Get team active and inactive seasons
        $router->get('/{id}/seasons', ['as' => 'team-seasons', 'uses' => 'TeamController@getTeamSeasons']);
    });

    /*
    |--------------------------------------------------------------------------
    | Scorecard Routes
    |--------------------------------------------------------------------------
    |
    | Scorecard management. Allows the use to create, delete and edit scorecards in
    | the database. Retrieval operations are the only ones supported without
    | providing an authentication token
    |
    */
    $router->group(['prefix' => 'scorecards'], function() use ($router) {
        // Get all the scorecards but with a reduced amount of data
        $router->get('/', ['as' => 'scorecards-all', 'uses' => 'ScorecardController@getAll']);
        // Get information on a specific game
        $router->get('/{id}', ['as' => 'scorecards-detail', 'uses' => 'ScorecardController@getGame']);
    });
    $router->group(['prefix' => 'scorecards', 'middleware' => ['auth']], function() use ($router) {
        // Create a score cards
        $router->post('/', ['as' => 'scorecards-create', 'uses' => 'ScorecardController@createGame']);
        // Update a scorecard
        $router->put('/{id}', ['as' => 'scorecards-update', 'uses' => 'ScorecardController@updateGame']);
        // Delete a game
        $router->delete('/{id}', ['as' => 'scorecards-delete', 'uses' => 'ScorecardController@deleteGame']);
    });

    /*
    |--------------------------------------------------------------------------
    | Scoreboard Routes
    |--------------------------------------------------------------------------
    |
    | Description
    |
    */
    $router->group(['prefix' => 'scoreboards'], function() use ($router) {
        // Get the current scoreboard
        $router->get('/current', ['as' => 'scoreboard-current', 'uses' => 'ScoreboardController@getCurrent']);
        // Get all the seasons available in the league
        $router->get('/all', ['as' => 'scoreboard-get-all', 'uses' => 'ScoreboardController@getAll']);
        // Get a scoreboard from the season slug
        $router->get('/{id}', ['as' => 'scoreboard-get', 'uses' => 'ScoreboardController@getScoreboard']);
    });

    /*
    |--------------------------------------------------------------------------
    | Tracker Routes
    |--------------------------------------------------------------------------
    |
    | Routes that handle traffic to do with the game tracker
    |
    */
    $router->group(['prefix' => 'tracker'], function() use ($router) {
        // Get the current tracker board
        $router->get('/current', ['as' => 'tracker-current', 'uses' => 'TrackerController@getCurrent']);
        // Get a tracker board for a specific season
        $router->get('/{id}', ['as' => 'tracker-get', 'uses' => 'TrackerController@getTracker']);
    });
});
