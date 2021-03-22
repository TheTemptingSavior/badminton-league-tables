<?php

use App\Models\Team;
use App\Models\User;
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
     * Retire a team
     *
     * @return void
     */
    function testRetireTeam()
    {
        $user = User::factory()->admin()->create();
        $team = Team::factory()->create();

        $result = $this->actingAs($user)
            ->json('PUT', '/api/teams/' . $team->id . '/retire', ['retired' => true])
            ->seeStatusCode(200)
            ->seeJsonStructure(['id', 'name', 'slug', 'retired_on', 'created_at', 'updated_at']);

        $data = json_decode($result->response->content());
        $this->assertNotNull($data->retired_on);
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
     * Attempt to retire a team without providing the proper data
     *
     * @return void
     */
    function testRetireTeamBadData()
    {
        $user = User::factory()->admin()->create();
        $team = Team::factory()->create();

        $this->actingAs($user)
            ->json('PUT', '/api/teams/' . $team->id . '/retire', ['baddata' => true])
            ->seeStatusCode(400);
    }
}
