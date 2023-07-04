<?php

use App\Models\Scorecard;
use App\Models\Season;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Testing\DatabaseMigrations;

class APITeamsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * List the users that are a part of this league
     *
     * @return void
     */
    function testListTeams()
    {
        Team::factory(10)->create();

        $result = $this->json('GET', '/api/teams')
            ->seeJsonStructure(
                ['current_page', 'first_page_url', 'from', 'next_page_url', 'path', 'per_page', 'prev_page_url', 'to']
            )
            ->seeStatusCode(200);
        $data = json_decode($result->response->content());

        $this->assertCount(10, $data->data);
        $this->assertEquals(1, $data->current_page);
        $this->assertEquals(15, $data->per_page);
        $this->assertStringEndsWith('/api/teams?page=1', $data->first_page_url);
        $this->assertNull($data->next_page_url);
        $this->assertNull($data->prev_page_url);
        $this->assertStringEndsWith('/api/teams', $data->path);
        $this->assertEquals(1, $data->from);
        $this->assertEquals(10, $data->to);
    }

    /**
     * Test listing teams that require multiple pages. Get the first page and
     * assert that the meta data is correct
     *
     * @return void
     */
    function testListTeamsPaged()
    {
        Team::factory(30)->create();

        $result = $this->json('GET', '/api/teams')
            ->seeJsonStructure(
                ['current_page', 'first_page_url', 'from', 'next_page_url', 'path', 'per_page', 'prev_page_url', 'to']
            )
            ->seeStatusCode(200);
        $data = json_decode($result->response->content());

        $this->assertCount(15, $data->data);
        $this->assertEquals(1, $data->current_page);
        $this->assertEquals(15, $data->per_page);
        $this->assertStringEndsWith('/api/teams?page=1', $data->first_page_url);
        $this->assertStringEndsWith('/api/teams?page=2', $data->next_page_url);
        $this->assertNull($data->prev_page_url);
        $this->assertStringEndsWith('/api/teams', $data->path);
        $this->assertEquals(1, $data->from);
        $this->assertEquals(15, $data->to);
    }

    /**
     * List the available teams where a second page of results is required
     *
     * @return void
     */
    function testListTeamsPageTwo()
    {
        Team::factory(40)->create();

        $result = $this->json('GET', '/api/teams?page=2')
            ->seeJsonStructure(
                ['current_page', 'first_page_url', 'from', 'next_page_url', 'path', 'per_page', 'prev_page_url', 'to']
            )
            ->seeStatusCode(200);
        $data = json_decode($result->response->content());

        $this->assertCount(15, $data->data);
        $this->assertEquals(2, $data->current_page);
        $this->assertEquals(15, $data->per_page);
        $this->assertStringEndsWith('/api/teams?page=1', $data->first_page_url);
        $this->assertStringEndsWith('/api/teams?page=3', $data->next_page_url);
        $this->assertStringEndsWith('/api/teams?page=1', $data->prev_page_url);
        $this->assertStringEndsWith('/api/teams', $data->path);
        $this->assertEquals(16, $data->from);
        $this->assertEquals(30, $data->to);
    }

    /**
     * List the seasons teams this league and changing the number of season
     * per page
     *
     * @return void
     */
    function testListTeamsPageLimit()
    {
        Team::factory(30)->create();

        $result = $this->json('GET', '/api/teams?per_page=10')
            ->seeJsonStructure(
                ['current_page', 'first_page_url', 'from', 'next_page_url', 'path', 'per_page', 'prev_page_url', 'to']
            )
            ->seeStatusCode(200);
        $data = json_decode($result->response->content());

        $this->assertCount(10, $data->data);
        $this->assertEquals(1, $data->current_page);
        $this->assertEquals(10, $data->per_page);
        $this->assertStringEndsWith('/api/teams?page=1', $data->first_page_url);
        $this->assertStringEndsWith('/api/teams?page=2', $data->next_page_url);
        $this->assertNull($data->prev_page_url);
        $this->assertStringEndsWith('/api/teams', $data->path);
        $this->assertEquals(1, $data->from);
        $this->assertEquals(10, $data->to);
    }

    /**
     * Return a list of teams, selecting a page from the middle of the results
     *
     * @return void
     */
    function testListTeamsMiddlePage()
    {
        Team::factory(40)->create();

        $result = $this->json('GET', '/api/teams?page=2')
            ->seeJsonStructure(
                ['current_page', 'first_page_url', 'from', 'next_page_url', 'path', 'per_page', 'prev_page_url', 'to']
            )
            ->seeStatusCode(200);
        $data = json_decode($result->response->content());

        $this->assertCount(15, $data->data);
        $this->assertEquals(2, $data->current_page);
        $this->assertEquals(15, $data->per_page);
        $this->assertStringEndsWith('/api/teams?page=1', $data->first_page_url);
        $this->assertStringEndsWith('/api/teams?page=3', $data->next_page_url);
        $this->assertStringEndsWith('/api/teams?page=1', $data->prev_page_url);
        $this->assertStringEndsWith('/api/teams', $data->path);
        $this->assertEquals(16, $data->from);
        $this->assertEquals(30, $data->to);
    }

    /**
     * Get a single team based upon its ID
     *
     * @return void
     */
    function testGetTeam()
    {
        $team = Team::factory()->create();
        $this->json('GET', '/api/teams/' . $team->id)
            ->seeStatusCode(200)
            ->seeJsonStructure(['id', 'name', 'slug'])
            ->seeJson(['id' => $team->id])
            ->seeJson(['name' => $team->name]);
    }

    /**
     * Attempt to get a team that doesn't exist
     *
     * @return void
     */
    function testGetTeamNonExist()
    {
        $this->json('GET', '/api/teams/999')
            ->seeStatusCode(404);
    }

    /**
     * Attempt to get a team with a bad ID
     *
     * @return void
     */
    function testGetTeamBadId()
    {
        $this->json('GET', '/api/teams/9i9')
            ->seeStatusCode(404);
    }

    /**
     * Create a new team
     *
     * @return void
     */
    function testCreateTeam()
    {
        $user = User::factory()->admin()->create();
        $result = $this->actingAs($user)
            ->json('POST', '/api/teams', ['name' => 'Hello World', 'slug' => 'hello-world'])
            ->seeStatusCode(201)
            ->seeJsonStructure(['id', 'name', 'slug', 'created_at', 'updated_at']);

        $data = json_decode($result->response->content());
        $this->assertEquals('Hello World', $data->name);
    }

    /**
     * Attempt to create a team without providing
     * the data
     * @return void
     */
    function testCreateTeamBadData()
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user)
            ->json('POST', '/api/teams', ['slug' => 'helloworld'])
            ->seeStatusCode(400);
    }

    /**
     * Attempt to create a team with a duplicate name
     *
     * @return void
     */
    function testCreateTeamNonUnique()
    {
        $user = User::factory()->admin()->create();
        $team = Team::factory()->create();

        $this->actingAs($user)
            ->json('POST', '/api/teams', ['name' => $team->name])
            ->seeStatusCode(409);
    }

    /**
     * Attempt to create a team without providing any authentication
     *
     * @return void
     */
    function testCreateTeamNoAuth()
    {
        $this->json('POST', '/api/teams', ['name' => 'Hello World'])
            ->seeStatusCode(401);
    }

    /**
     * Attempt to create a team without being an admin
     * user
     *
     * @return void
     */
    function testCreateTeamNonAdmin()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->json('POST', '/api/teams', ['name' => 'World Hello'])
            ->seeStatusCode(403);
    }

    /**
     * Attempt to retire a team without providing authentication
     *
     * @return void
     */
    function testRetireTeamNoAuth()
    {
        $team = Team::factory()->create();

        $this->json('PUT', '/api/teams/' . $team->id . '/retire', ['retired' => true])
            ->seeStatusCode(401);
    }

    /**
     * Attempt to retire a team without being an admin user
     *
     * @return void
     */
    function testRetireTeamNoAdmin()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();

        $this->actingAs($user)
            ->json('PUT', '/api/teams/' . $team->id . '/retire', ['retired' => true])
            ->seeStatusCode(403);
    }

    /**
     * Attempt to retire a team with an incorrect team ID
     *
     * @return void
     */
    function testRetireTeamBadTeamId()
    {
        $user = User::factory()->admin()->create();

        $data = Array(
            Array('season' => 1, 'active' => true)
        );
        $this->actingAs($user)
            ->json('PUT', '/api/teams/9999/retire', $data)
            ->seeStatusCode(404);
    }

    /**
     * Attempt to retire a team without providing any data
     *
     * @return void
     */
    function testRetireTeamNoData()
    {
        $user = User::factory()->admin()->create();
        $team = Team::factory()->create();

        $this->actingAs($user)
            ->json('PUT', '/api/teams/'.$team->id.'/retire')
            ->seeStatusCode(400);
        $this->actingAs($user)
            ->json('PUT', '/api/teams/'.$team->id.'/retire', [])
            ->seeStatusCode(400);
    }

    /**
     * Retire a team from the specified season
     *
     * @return void
     */
    function testRetireTeamFromOneSeason()
    {
        $user = User::factory()->admin()->create();
        $team = Team::factory()->create();
        $season = Season::factory()->create();

        // Manually create the link
        DB::table('season_teams')->insert([
            'season_id' => $season->id,
            'team_id' => $team->id
        ]);

        // Retire the team
        $data = Array(
            'data' => Array(
                Array('season' => $season->id, 'active' => false)
            )
        );
        $this->actingAs($user)
            ->json('PUT', '/api/teams/'.$team->id.'/retire', $data)
            ->seeStatusCode(200);

        // Check the database to make sure it was actually deleted
        $this->assertCount(
            0,
            DB::table('season_teams')->get()->toArray()
        );
    }

    /**
     * Attempt to retire a team from a season without providing the
     * correct data for the 'active'
     *
     * @return void
     */
    function testRetireTeamFromOneSeasonBadActive()
    {
        $user = User::factory()->admin()->create();
        $team = Team::factory()->create();
        $season = Season::factory()->create();

        // Manually create the link
        DB::table('season_teams')->insert([
            'season_id' => $season->id,
            'team_id' => $team->id
        ]);

        // Retire the team
        $data = Array(
            'data' => Array(
                Array('season' => $season->id, 'active' => 'seventeen')
            )
        );
        $this->actingAs($user)
            ->json('PUT', '/api/teams/'.$team->id.'/retire', $data)
            ->seeStatusCode(400);
    }

    /**
     * Attempt to retire a team from a season without providing the
     * correct data for the 'active'
     *
     * @return void
     */
    function testRetireTeamFromSeasonsMissingData()
    {
        $user = User::factory()->admin()->create();
        $team = Team::factory()->create();
        $season = Season::factory()->count(5)->create();

        // Manually create the link
        DB::table('season_teams')->insert([
            'season_id' => $season[0]->id,
            'team_id' => $team->id
        ]);

        // Retire the team
        $data = Array(
            'data' => Array(
                Array('season' => $season[0]->id, 'active' => false),
                Array('season' => $season[1]->id),
                Array('season' => $season[2]->id, 'active' => false, 'other' => 'type')
            )
        );
        $this->actingAs($user)
            ->json('PUT', '/api/teams/'.$team->id.'/retire', $data)
            ->seeStatusCode(400);
    }

    /**
     * Attempt to retire team from the specified season without the
     * season existing
     *
     * @return void
     */
    function testRetireTeamFromOneBadSeason()
    {
        $user = User::factory()->admin()->create();
        $team = Team::factory()->create();

        $data = Array(
            'data' => Array(
                Array('season' => 999, 'active' => false)
            )
        );
        $this->actingAs($user)
            ->json('PUT', '/api/teams/'.$team->id.'/retire', $data)
            ->seeStatusCode(404);
    }

    /**
     * Retire a team from multiple seasons
     *
     * @return void
     */
    function testRetireTeamManySeasons()
    {
        $user = User::factory()->admin()->create();
        $team = Team::factory()->create();
        $seasons = Season::factory()->count(5)->create();

        foreach($seasons as $s) {
            DB::table('season_teams')->insert([
                'season_id' => $s->id,
                'team_id' => $team->id
            ]);
        }

        $data = Array(
            'data' => Array(
                Array('active' => false, 'season' => $seasons[0]->id),
                Array('active' => false, 'season' => $seasons[1]->id),
            )
        );
        $this->actingAs($user)
            ->json('put', '/api/teams/'.$team->id.'/retire', $data)
            ->seeStatusCode(200);

        $this->assertNull(
            DB::table('season_teams')
                ->where('team_id', '=', $team->id)
                ->where('season_id', '=', $seasons[0]->id)
                ->first()
        );
        $this->assertNull(
            DB::table('season_teams')
                ->where('team_id', '=', $team->id)
                ->where('season_id', '=', $seasons[1]->id)
                ->first()
        );
        $this->assertNotNull(
            DB::table('season_teams')
                ->where('team_id', '=', $team->id)
                ->where('season_id', '=', $seasons[2]->id)
                ->first()
        );
        $this->assertNotNull(
            DB::table('season_teams')
                ->where('team_id', '=', $team->id)
                ->where('season_id', '=', $seasons[3]->id)
                ->first()
        );
        $this->assertNotNull(
            DB::table('season_teams')
                ->where('team_id', '=', $team->id)
                ->where('season_id', '=', $seasons[4]->id)
                ->first()
        );
    }

    /**
     * Multiple actions to perform on the team season data.
     * Includes two retire and one make active again
     *
     * @return void
     */
    function testRetireTeamManyActions()
    {
        $user = User::factory()->admin()->create();
        $team = Team::factory()->create();
        $seasons = Season::factory()->count(5)->create();

        DB::table('season_teams')->insert([
            'season_id' => $seasons[0]->id,
            'team_id' => $team->id
        ]);
        DB::table('season_teams')->insert([
            'season_id' => $seasons[1]->id,
            'team_id' => $team->id
        ]);

        $data = Array(
            'data' => Array(
                Array('active' => true,  'season' => $seasons[0]->id),
                Array('active' => false, 'season' => $seasons[1]->id),
                Array('active' => true,  'season' => $seasons[2]->id),
                Array('active' => false, 'season' => $seasons[3]->id),
                Array('active' => false, 'season' => $seasons[4]->id)
            )
        );

        $this->actingAs($user)
            ->json('put', '/api/teams/'.$team->id.'/retire', $data)
            ->seeStatusCode(200);

        $this->assertNotNull(
            DB::table('season_teams')
                ->where('team_id', '=', $team->id)
                ->where('season_id', '=', $seasons[0]->id)
                ->first()
        );
        $this->assertNull(
            DB::table('season_teams')
                ->where('team_id', '=', $team->id)
                ->where('season_id', '=', $seasons[1]->id)
                ->first()
        );
        $this->assertNotNull(
            DB::table('season_teams')
                ->where('team_id', '=', $team->id)
                ->where('season_id', '=', $seasons[2]->id)
                ->first()
        );
        $this->assertNull(
            DB::table('season_teams')
                ->where('team_id', '=', $team->id)
                ->where('season_id', '=', $seasons[3]->id)
                ->first()
        );
        $this->assertNull(
            DB::table('season_teams')
                ->where('team_id', '=', $team->id)
                ->where('season_id', '=', $seasons[4]->id)
                ->first()
        );
    }

    /**
     * Multiple actions to perform on the team season data.
     * Includes two retire and one make active again. One of
     * which fails
     *
     * @return void
     */
    function testRetireTeamManyActionsOnFails()
    {
        $user = User::factory()->admin()->create();
        $team = Team::factory()->create();

        // Check database to make sure changes didn't persist
    }

    /**
     * Attempt to retire a team from a season where they
     * have scorecards
     *
     * @return void
     */
    function testRetireTeamHasScorecards()
    {
        $user = User::factory()->admin()->create();
        $team = Team::factory()->create();
        $otherTeam = Team::factory()->create();
        $season = new Season;
        $season->start = '2018-09-01';
        $season->end = '2019-08-31';
        $season->slug = '18-19';
        $season->save();
        $sc = Scorecard::factory()
            ->setHomeTeam($team->id)
            ->setAwayTeam($otherTeam->id)
            ->setDatePlayed('2018-11-07')
            ->create();

        // Manually create the link
        DB::table('season_teams')->insert([
            'season_id' => $season->id,
            'team_id' => $team->id
        ]);
        DB::table('season_teams')->insert([
            'season_id' => $season->id,
            'team_id' => $otherTeam->id
        ]);

        // Retire the team
        $data = Array(
            'data' => Array(
                Array('season' => $season->id, 'active' => false)
            )
        );
        $this->actingAs($user)
            ->json('PUT', '/api/teams/'.$team->id.'/retire', $data)
            ->seeStatusCode(400)
            ->seeJsonStructure(['error']);

        // Row should NOT have been deleted. There are scorecards
        $this->assertNotNull(
            DB::table('season_teams')
                ->where('season_id', '=', $season->id)
                ->where('team_id', '=', $team->id)
                ->first()
        );
    }
}
