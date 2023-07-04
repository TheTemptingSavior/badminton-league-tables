<?php

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
        factory('App\Models\Team', 10)->create();

        $result = $this->json('GET', '/api/teams')
            ->seeStatusCode(200)
            ->seeJsonStructure([['id', 'name', 'slug', 'created_at', 'updated_at', 'retired_on']]);

        $data = json_decode($result->response->content());
        $this->assertCount(10, $data);
    }

    /**
     * Get a single team based upon its ID
     *
     * @return void
     */
    function testGetTeam()
    {
        $team = factory('App\Models\Team')->create();
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
        $this->json('GET', '/api/team/999')
            ->seeStatusCode(404);
    }

    /**
     * Attempt to get a team with a bad ID
     *
     * @return void
     */
    function testGetTeamBadId()
    {
        $this->json('GET', '/api/team/helloworld')
            ->seeStatusCode(404);
    }

    /**
     * Create a new team
     *
     * @return void
     */
    function testCreateTeam()
    {
        $user = factory('App\Models\User')->state('admin')->create();
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
     * TODO: Should return a 400 but returns a 422
     * @return void
     */
    function testCreateTeamBadData()
    {
        $user = factory('App\Models\User')->state('admin')->create();
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
        $user = factory('App\Models\User')->state('admin')->create();
        $team = factory('App\Models\Team')->create();

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
        $user = factory('App\Models\User')->create();

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
        $user = factory('App\Models\User')->state('admin')->create();
        $team = factory('App\Models\Team')->create();

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
        $team = factory('App\Models\Team')->create();

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
        $user = factory('App\Models\User')->create();
        $team = factory('App\Models\Team')->create();

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
        $user = factory('App\Models\User')->state('admin')->create();
        $team = factory('App\Models\Team')->create();

        $this->actingAs($user)
            ->json('PUT', '/api/teams/' . $team->id . '/retire', ['baddata' => true])
            ->seeStatusCode(400);
    }
}
