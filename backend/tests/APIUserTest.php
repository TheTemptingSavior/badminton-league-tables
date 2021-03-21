<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Testing\DatabaseMigrations;

class APIUserTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test listing the users
     *
     * @return void
     */
    function testListUsers()
    {
        $user = User::factory()->admin()->create();

        $result = $this->actingAs($user)
            ->json('GET', '/api/users')
            ->seeStatusCode(200);

        $data = $result->response->original;
        $this->assertCount(1, $data, 'Incorrect number of users');
    }

    /**
     * Testing listing many users
     *
     * @return void
     */
    function testListUsersMany()
    {
        $user = User::factory()->admin()->create();
        User::factory()->count(10)->create();

        $result = $this->actingAs($user)
            ->json('GET', '/api/users')
            ->seeStatusCode(200);

        $data = $result->response->original;
        $this->assertCount(11, $data, 'Incorrect number of users');
    }

    /**
     * Test listing users that require multiple pages. Get the first page and
     * assert that the meta data is correct
     *
     * @return void
     */
    function testListUsersPaged()
    {
        // TODO: Implement APIUserTest::testListUsersPaged
    }

    /**
     * List the registered users where a second page of results is required
     *
     * @return void
     */
    function testListUsersPageTwo()
    {
        // TODO: Implement APIUserTest::testListUsersPageTwo
    }

    /**
     * List the users in this league and changing the number of users
     * per page
     *
     * @return void
     */
    function testListUsersPageLimit()
    {
        // TODO: Implement APIUserTest::testListUsersPageLimit
    }

    /**
     * Return a list of user, selecting a page from the middle of the results
     *
     * @return void
     */
    function testListUsersMiddlePage()
    {
        // TODO: Implement APIUserTest::testListUsersMiddlePage
    }

    /**
     * Attempt to request a protected endpoint without
     * providing an authentication token
     *
     * @return void
     */
    function testListUsersNoAuth()
    {
        $this->json('GET', '/api/users')
            ->seeStatusCode(401);
    }

    /**
     * Get a single user based on their ID
     *
     * @return void
     */
    function testGetUser()
    {
        $user = User::factory()->admin()->create();

        $this->actingAs($user)
            ->json('GET', '/api/users/' . $user->id)
            ->seeStatusCode(200)
            ->seeJson(["id" => $user->id])
            ->seeJson(["username" => $user->username]);
    }

    /**
     * Attempt to get details on a user that doesn't exist
     *
     * @return void
     */
    function testGetUserNotExist()
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user)
            ->json('GET', '/api/users/' . ($user->id+1))
            ->seeStatusCode(404);
    }

    /**
     * Attempt to retrieve a user without providing an
     * integer as an ID
     *
     * @return void
     */
    function testGetUserBadId()
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user)
            ->json('GET', '/api/users/helloworld')
            ->seeStatusCode(404);
    }

    /**
     * Attempt to retrieve a user without providing
     * authentication
     *
     * @return void
     */
    function testGetUserNoAuth()
    {
        $user = User::factory()->make();

        $this->json('GET', '/api/users/' . $user->id)
            ->seeStatusCode(401);
    }

    /**
     * Create a new user through the API
     *
     * @return void
     */
    function testCreateUser()
    {
        $user = User::factory()->admin()->make();
        $this->actingAs($user)
            ->json('POST', '/api/users', ['username' => 'test', 'password' => 'test'])
            ->seeStatusCode(201)
            ->seeInDatabase('users', ['username' => 'test']);
    }

    /**
     * Attempt to create a new user without providing valid
     * authentication
     *
     * @return void
     */
    function testCreateUserNoAuth()
    {
        $this->json('POST', '/api/users', ['username' => 'test', 'password' => 'test'])
            ->seeStatusCode(401);
    }

    /**
     * Attempt to create a new user with a token that
     * isn't from an admin user
     *
     * @return void
     */
    function testCreateUserNoAdmin()
    {
        $user = User::factory()->make();
        $this->actingAs($user)
            ->json('POST', '/api/users', ['username' => 'test', 'password' => 'test'])
            ->seeStatusCode(403);
    }

    /**
     * Delete a user from the database
     *
     * @return void
     */
    function testDeleteUser()
    {
        $user = User::factory('App\Models\User')->admin()->create();
        $deleteMe = User::factory('App\Models\User')->create();
        $uid = $deleteMe->id;

        $this->actingAs($user)
            ->delete('/api/users/' . $uid)
            ->seeStatusCode(204);

        $deleted = DB::table('users')
            ->where('id', $uid)
            ->get()
            ->count();
        $this->assertEquals(0, $deleted);
    }

    /**
     * Attempt to delete a user without providing authentication
     *
     * @return void
     */
    function testDeleteUserNoAuth()
    {
        $deleteMe = User::factory()->create();
        $this->json('DELETE', 'api/users/' . $deleteMe->id)
            ->seeStatusCode(401);
    }

    /**
     * Attempt to delete a user that doesn't exist
     */
    function testDeleteUserNonExist()
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user)
            ->json('DELETE', '/api/users/999')
            ->seeStatusCode(404);
    }

    /**
     * Attempt to delete a user with a bad ID
     *
     * @return void
     */
    function testDeleteUserBadId()
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user)
            ->json('DELETE', '/api/users/helloworld')
            ->seeStatusCode(404);
    }

    /**
     * Attempt to delete a user without admin privileges
     *
     * @return void
     */
    function testDeleteUserNonAdmin()
    {
        $user = User::factory()->create();
        $deleteMe = User::factory()->create();
        $this->actingAs($user)
            ->json('DELETE', '/api/users/' . $deleteMe->id)
            ->seeStatusCode(403);
    }

    /**
     * Update a users information
     * @return void
     */
    function testUpdateUser()
    {
        $user = User::factory()->admin()->create();

        $result = $this->actingAs($user)
            ->json('PUT', '/api/users/' . $user->id, ['username' => 'NewUsername'])
            ->seeStatusCode(200)
            ->seeJsonContains(["username" => "NewUsername"]);

        $data = json_decode($result->response->content());
        $this->assertNotNull($data->updated_at);
    }

    /**
     * Attempt to update a different users account
     *
     * @return void
     */
    function testUpdateOtherUser()
    {
        $user = User::factory()->create();
        $updateMe = User::factory()->create();

        $this->actingAs($user)
            ->json('PUT', '/api/users/' . $updateMe->id, ['username' => 'NewUsername'])
            ->seeStatusCode(403);
    }

    /**
     * Update a different users account by using an admin account
     *
     * @return void
     */
    function testUpdateOtherUserAsAdmin()
    {
        $user = User::factory()->admin()->create();
        $updateMe = User::factory()->create();

        $this->actingAs($user)
            ->json('PUT', '/api/users/' . $updateMe->id, ['username' => 'NewUsername'])
            ->seeStatusCode(200)
            ->seeJsonContains(['username' => 'NewUsername']);
    }

    /**
     * Attempt to change a users username to one that already
     * exists in the database
     *
     * @return void
     */
    function testUpdateUserDuplicateData()
    {
        $user = User::factory()->create();
        $userTwo = User::factory()->create();

        $this->actingAs($user)
            ->json('PUT', '/api/users/' . $user->id, ['username' => $userTwo->username])
            ->seeStatusCode(409);
    }

    /**
     * Attempt to update a user account without providing any
     * authentication
     */
    function testUpdateUserNoAuth()
    {
        $user = User::factory()->create();

        $this->json('PUT', '/api/users/' . $user->id, ['username' => 'NewUsername'])
            ->seeStatusCode(401);
    }
}
