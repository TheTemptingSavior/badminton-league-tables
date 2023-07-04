<?php

use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Testing\DatabaseMigrations;

class APIScorecardTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * List all the scorecards in the system
     *
     * @return void
     */
    function testGetAll()
    {
        factory('App\Models\Team', 6)->create();
        factory('App\Models\Scorecard', 5)->create();

        $response = $this->json('GET', '/api/scorecards')
            ->seeJsonStructure(
                ['current_page', 'first_page_url', 'from', 'next_page_url', 'path', 'per_page', 'prev_page_url', 'to']
            )
            ->seeStatusCode(200);
        $data = json_decode($response->response->content());

        $this->assertCount(5, $data->data);
        $this->assertEquals(1, $data->current_page);
        $this->assertEquals(15, $data->per_page);
        $this->assertStringEndsWith('/api/scorecards?page=1', $data->first_page_url);
        $this->assertNull($data->next_page_url);
        $this->assertNull($data->prev_page_url);
        $this->assertStringEndsWith('/api/scorecards', $data->path);
        $this->assertEquals(1, $data->from);
        $this->assertEquals(5, $data->to);
    }

    /**
     * Get all the scorecards but on the second page returned
     *
     * @return void
     */
    function testGetAllPageTwo()
    {
        factory('App\Models\Team', 6)->create();
        factory('App\Models\Scorecard', 20)->create();

        $response = $this->json('GET', '/api/scorecards')
            ->seeJsonStructure(
                ['current_page', 'first_page_url', 'from', 'next_page_url', 'path', 'per_page', 'prev_page_url', 'to']
            )
            ->seeStatusCode(200);
        $data = json_decode($response->response->content());

        $this->assertCount(15, $data->data);
        $this->assertEquals(1, $data->current_page);
        $this->assertEquals(15, $data->per_page);
        $this->assertStringEndsWith('/api/scorecards?page=1', $data->first_page_url);
        $this->assertStringEndsWith('/api/scorecards?page=2', $data->next_page_url);
        $this->assertNull($data->prev_page_url);
        $this->assertStringEndsWith('/api/scorecards', $data->path);
        $this->assertEquals(1, $data->from);
        $this->assertEquals(15, $data->to);
    }

    /**
     * Get all the scorecards but change the number of items per page
     *
     * @return void
     */
    function testGetAllDifferentPageLimit()
    {
        // TODO: Implement APIScorecardTest::testGetAllDifferentPageLimit()
    }

    /**
     * Get all the scorecards but choose a page that has both the next and
     * previous page urls set
     *
     * @return void
     */
    function testGetAllMiddlePage()
    {
        // TODO: Implement APIScorecardTest::testGetAllMiddlePage()
    }

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
     * Attempt to get a game providing a non-integer ID
     *
     * @return void
     */
    function testGetGameBadId()
    {
        $this->json('GET', '/api/scorecards/helloworld')
            ->seeStatusCode(404);
    }

    /**
     * Attempt to retrieve a scorecard that does not exist
     *
     * @return void
     */
    function testGetGameNoExist()
    {
        $this->json('GET', '/api/scorecards/999')
            ->seeStatusCode(404);
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

    /**
     * Attempt to create a scorecard with both the home and
     * away team having the same ID
     *
     * @return void
     */
    function testCreateGameBadTeamId()
    {
        $user = factory('App\Models\User')->state('admin')->create();
        $homeTeam = factory('App\Models\Team')->create();
        factory('App\Models\Team', 5)->create();
        $season = new \App\Models\Season;
        $season->start = '2018-09-01';
        $season->end = '2019-08-31';
        $season->slug = '18-19';
        $season->save();

        $gameData = [
            'home_team' => $homeTeam->id,
            'away_team' => $homeTeam->id,
            'date_played' => '2018-11-11',
            'home_points' => 3,
            'away_points' => 6
        ];
        $this->actingAs($user)
            ->json('POST', '/api/scorecards', $gameData)
            ->seeStatusCode(400);
    }

    /**
     * Attempt to create a scorecard with the home and away
     * points not adding up to 9
     *
     * @return void
     */
    function testCreateGameBadPoints()
    {
        $user = factory('App\Models\User')->state('admin')->create();
        $homeTeam = factory('App\Models\Team')->create();
        $awayTeam = factory('App\Models\Team')->create();
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
            'away_points' => 6
        ];
        $this->actingAs($user)
            ->json('POST', '/api/scorecards', $gameData)
            ->seeStatusCode(400);
    }

    /**
     * Attempt to create a scorecard with the home and away
     * points add to 9 but include negative numbers
     *
     * @return void
     */
    function testCreateGameBadPointsNegative()
    {
        $user = factory('App\Models\User')->state('admin')->create();
        $homeTeam = factory('App\Models\Team')->create();
        $awayTeam = factory('App\Models\Team')->create();
        $season = new \App\Models\Season;
        $season->start = '2018-09-01';
        $season->end = '2019-08-31';
        $season->slug = '18-19';
        $season->save();

        $gameData = [
            'home_team' => $homeTeam->id,
            'away_team' => $awayTeam->id,
            'date_played' => '2018-11-11',
            'home_points' => 10,
            'away_points' => -1
        ];
        $this->actingAs($user)
            ->json('POST', '/api/scorecards', $gameData)
            ->seeStatusCode(400);
    }

    /**
     * Attempt to create a game without providing authentication
     *
     * @return void
     */
    function testCreateGameNoAuth()
    {
        factory('App\Models\Team', 6)->create();
        $scorecard = factory('App\Models\Scorecard')->create();

        $this->json('POST', '/api/scorecards', $scorecard->toArray())
            ->seeStatusCode(401);
    }

    /**
     * Attempt to create a game without providing being an
     * admin user
     *
     * @return void
     */
    function testCreateGameNoAdmin()
    {
        $user = factory('App\Models\User')->create();

        factory('App\Models\Team', 6)->create();
        $scorecard = factory('App\Models\Scorecard')->create();

        $this->actingAs($user)
            ->json('POST', '/api/scorecards', $scorecard->toArray())
            ->seeStatusCode(403);
    }

    /**
     * Delete a scorecard
     *
     * @return void
     */
    function testDeleteScorecard()
    {
        $user = factory('App\Models\User')->state('admin')->create();
        factory('App\Models\Team', 6)->create();
        $scorecard = factory('App\Models\Scorecard')->create();

        $this->actingAs($user)
            ->json('DELETE', '/api/scorecards/'.$scorecard->id)
            ->seeStatusCode(204);

        $this->assertNull(DB::table('scorecards')->where('id', '=', $scorecard->id)->first());
    }

    /**
     * Attempt to delete a scorecard with a bad ID
     *
     * @return void
     */
    function testDeleteScorecardBadId()
    {
        $user = factory('App\Models\User')->state('admin')->create();
        factory('App\Models\Team', 6)->create();
        $scorecard = factory('App\Models\Scorecard')->create();

        $this->actingAs($user)
            ->json('DELETE', '/api/scorecards/helloworld')
            ->seeStatusCode(404);
    }

    /**
     * Attempt to delete a scorecard with an ID
     * that does not exist
     *
     * @return void
     */
    function testDeleteScorecardNoExist()
    {
        $user = factory('App\Models\User')->state('admin')->create();
        factory('App\Models\Team', 6)->create();
        $scorecard = factory('App\Models\Scorecard')->create();

        $this->actingAs($user)
            ->json('DELETE', '/api/scorecards/999')
            ->seeStatusCode(404);
    }

    /**
     * Attempt to delete a scorecard without providing
     * authentication
     *
     * @return void
     */
    function testDeleteScorecardNoAuth()
    {
        factory('App\Models\Team', 6)->create();
        $scorecard = factory('App\Models\Scorecard')->create();

        $this->json('DELETE', '/api/scorecards/'.$scorecard->id)
            ->seeStatusCode(401);
    }

    /**
     * Attempt to delete a scorecard without being an
     * admin user
     *
     * @return void
     */
    function testDeleteScorecardNoAdmin()
    {
        $user = factory('App\Models\User')->create();
        factory('App\Models\Team', 6)->create();
        $scorecard = factory('App\Models\Scorecard')->create();

        $this->actingAs($user)
            ->json('DELETE', '/api/scorecards/'.$scorecard->id)
            ->seeStatusCode(403);
    }


}