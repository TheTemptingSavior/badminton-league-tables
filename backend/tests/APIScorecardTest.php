<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class APIScorecardTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Retrieve a game based upon its ID
     *
     * @return void
     */
    function testGetGame()
    {
        // Create 6 teams for the game to choose from
        factory('App\Models\Team', 6)->create();
        $scorecard = factory('App\Models\Scorecard')->create();

        $this->json('GET', '/api/scorecards/' . $scorecard->id)
            ->seeStatusCode(200)
            ->seeJsonStructure(['id', 'home_team', 'away_team', 'date_played', 'home_points', 'away_points']);
    }

    /**
     * Create a game with only the required data
     *
     * @return void
     */
    function testCreateGame()
    {
        $user = factory('App\Models\User')->state('admin')->create();
        $homeTeam = factory('App\Models\Team')->create();
        $awayTeam = factory('App\Models\Team')->create();
        // Create a season for the scorecard to go in
        $season = new \App\Models\Season;
        $season->start = '2018-09-01';
        $season->end = '2019-08-31';
        $season->slug = '18-19';
        $season->save();
        $gameData = [
            'home_team' => $homeTeam->id,
            'away_team' => $awayTeam->id,
            'date_played' => '2018-11-11',
            'home_points' => 6,
            'away_points' => 3,
        ];

        $result = $this->actingAs($user)
            ->json('POST', '/api/scorecards', $gameData)
            ->seeStatusCode(201)
            ->seeJsonStructure(['message', 'warnings']);
        $data = json_decode($result->response->content());

        $result = $this->json('GET', '/api/scorecards/' . $data->id)
            ->seeStatusCode(200)
            ->seeJson(['home_team' => '' . $homeTeam->id . ''])
            ->seeJson(['away_team' => '' . $awayTeam->id . '']);
        $data = json_decode($result->response->content());

        $this->assertNull($data->home_player_1);
        $this->assertNull($data->away_player_1);
        $this->assertNull($data->game_one_v_one_home_one);

    }

    /**
     * Create a scorecard with all the required fields and some
     * of the optional data filled in
     *
     * @return void
     */
    function testCreateGamePartialOptionalData()
    {
        $user = factory('App\Models\User')->state('admin')->create();
        factory('App\Models\Team', 6)->create();
        $season = new \App\Models\Season;
        $season->start = '2018-09-01';
        $season->end = '2019-08-31';
        $season->slug = '18-19';
        $season->save();

        // Make a scorecard but do not persist it to the database
        $gameData = factory('App\Models\Scorecard')->make();
        $gameData->date_played = '2018-11-11';

        $result = $this->actingAs($user)
            ->json('POST', '/api/scorecards', $gameData->toArray())
            ->seeStatusCode(201)
            ->seeJsonStructure(['message', 'id', 'warnings']);

        $data = json_decode($result->response->content());
        $this->assertNull($data->warnings);
    }

    /**
     * Attempt to create a game providing a date that is not in any of the
     * currently registered seasons
     *
     * @return void
     */
    function testCreateGameBadDate()
    {
        $user = factory('App\Models\User')->state('admin')->create();
        factory('App\Models\Team', 6)->create();

        // Make a scorecard but do not persist it to the database
        // The randomly generated date has no season in the database
        $gameData = factory('App\Models\Scorecard')->make();
        $this->actingAs($user)
            ->json('POST', '/api/scorecards', $gameData->toArray())
            ->seeStatusCode(400);
    }
}
