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
    public function testListUsers()
    {
        $user = factory('App\Models\User')->state('admin')->create();

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
    public function testListUsersMany()
    {
        $user = factory('App\Models\User')->state('admin')->create();
        $manyUsers = factory('App\Models\User', 10)->create();

        $result = $this->actingAs($user)
            ->json('GET', '/api/users')
            ->seeStatusCode(200);

        $data = $result->response->original;
        $this->assertCount(11, $data, 'Incorrect number of users');
    }

    /**
     * Attempt to request a protected endpoint without
     * providing an authentication token
     *
     * @return void
     */
    public function testListUsersNoAuth()
    {
        $result = $this->json('GET', '/api/users')
            ->seeStatusCode(401);
    }

    /**
     * Get a single user based on their ID
     *
     * @return void
     */
    public function testGetUser()
    {
        $user = factory('App\Models\User')->state('admin')->create();
        $result = $this->actingAs($user)
            ->json('GET', '/api/users/'.$user->id)
            ->seeStatusCode(200)
            ->seeJsonContains(["id" => $user->id])
            ->seeJsonContains(["username" => $user->username]);
    }

    /**
     * Attempt to get details on a user that doesn't exist
     *
     * @return void
     */
    public function testGetUserNotExist()
    {
        $user = factory('App\Models\User')->state('admin')->create();
        $result = $this->actingAs($user)
            ->json('GET', '/api/users/'.($user->id+1))
            ->seeStatusCode(404);
    }

    /**
     * Attempt to retrieve a user without providing an
     * integer as an ID
     *
     * @return void
     */
    public function testGetUserBadId()
    {
        $user = factory('App\Models\User')->state('admin')->create();
        $result = $this->actingAs($user)
            ->json('GET', '/api/users/helloworld')
            ->seeStatusCode(404);
    }

    /**
     * Attempt to retrieve a user without providing
     * authentication
     *
     * @return void
     */
    public function testGetUserNoAuth()
    {
        $user = factory('App\Models\User')->make();

        $this->json('GET', '/api/users/'.$user->id)
            ->seeStatusCode(401);
    }

    /**
     * Create a new user through the API
     *
     * @return void
     */
    public function testCreateUser()
    {
        $user = factory('App\Models\User')->state('admin')->make();
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
    public function testCreateUserNoAuth()
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
    public function testCreateUserNoAdmin()
    {
        $user = factory('App\Models\User')->make();
        $this->actingAs($user)
            ->json('POST', '/api/users', ['username' => 'test', 'password' => 'test'])
            ->seeStatusCode(403);
    }

    /**
     * Delete a user from the database
     *
     * @return void
     */
    public function testDeleteUser()
    {
        $user = factory('App\Models\User')->state('admin')->make();
        $deleteMe = factory('App\Models\User')->make();
        $uid = $deleteMe->id;
        $this->actingAs($user)
            ->delete('/api/users/'.$uid)
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
    public function testDeleteUserNoAuth()
    {
        $deleteMe = factory('App\Models\User')->make();
        $this->json('DELETE', 'api/users/'.$deleteMe->id)
            ->seeStatusCode(401);
    }

    /**
     * Attempt to delete a user that doesn't exist
     */
    public function testDeleteUserNonExist()
    {
        $user = factory('App\Models\User')->state('admin')->make();
        $this->actingAs($user)
            ->json('DELETE', '/api/users/999')
            ->seeStatusCode(404);
    }

    /**
     * Attempt to delete a user with a bad ID
     *
     * @return void
     */
    public function testDeleteUserBadId()
    {
        $user = factory('App\Models\User')->state('admin')->make();
        $this->actingAs($user)
            ->json('DELETE', '/api/user/helloworld')
            ->seeStatusCode(404);
    }

    /**
     * Attempt to delete a user without admin privileges
     *
     * @return void
     */
    public function testDeleteUserNonAdmin()
    {
        $user = factory('App\Models\User')->make();
        $deleteMe = factory('App\Models\User')->make();
        $this->actingAs($user)
            ->json('DELETE', '/api/users/'.$deleteMe->id)
            ->seeStatusCode(403);
    }

    /**
     * Update a users information
     *
     * @return void
     */
    public function testUpdateUser()
    {
        $user = factory('App\Models\User')->state('admin')->make();

        $result = $this->actingAs($user)
            ->put('/api/users/'.$user->id, ['username' => 'NewUsername'])
            ->seeStatusCode(200)
            ->seeJsonContains(['username' => 'NewUsername']);
//        $result = $this->actingAs($user)
//            ->json('PUT', '/api/users/'.$user->id, ['username' => 'NewUsername'])
//            ->seeStatusCode(200)
//            ->seeJsonContains(["username" => "NewUsername"]);
        $data = json_decode($result->response->content());
        $this->assertNotNull($data['updated_on']);
    }

    /**
     * Attempt to update a different users account
     *
     * @return void
     */
    public function testUpdateOtherUser()
    {
        $user = factory('App\Models\User')->make();
        $updateMe = factory('App\Models\User')->make();

        $this->actingAs($user)
            ->json('PUT', '/api/users/'.$updateMe->id, ['username' => 'NewUsername'])
            ->seeStatusCode(403);
    }

    /**
     * Update a different users account by using an admin account
     *
     * @return void
     */
    public function testUpdateOtherUserAsAdmin()
    {
        $user = factory('App\Models\User')->state('admin')->make();
        $updateMe = factory('App\Models\User')->make();

        $this->actingAs($user)
            ->json('PUT', '/api/users/'.$updateMe->id, ['username' => 'NewUsername'])
            ->seeStatusCode(200)
            ->seeJsonContains(['username' => 'NewUsername']);
    }

    /**
     * Attempt to change a users username to one that already
     * exists in the database
     *
     * @return void
     */
    public function testUpdateUserDuplicateData()
    {
        $user = factory('App\Models\User')->make();
        $userTwo = factory('App\Models\User')->make();

        $this->actingAs($user)
            ->json('PUT', '/api/users/'.$user->id, ['username' => $userTwo->username])
            ->seeStatusCode(400);
    }

    /**
     * Attempt to update a user account without providing any
     * authentication
     */
    public function testUpdateUserNoAuth()
    {
        $user = factory('App\Models\User')->make();

        $this->json('PUT', '/api/users/'.$user->id, ['username' => 'NewUsername'])
            ->seeStatusCode(401);
    }
}
