<?php

use App\Models\User;
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
        $this->assertCount(1, $data);
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
        $this->assertCount(11, $data);
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

    public function testDeleteUser()
    {
        // + Delete a user
    }

    public function testDeleteUserNoAuth()
    {
        // - Delete a user without a token
    }

    public function testDeleteUserNonExist()
    {
        // - Delete a user that doesn't exist
    }

    public function testDeleteUserBadId()
    {
        // - Delete a user with a bad ID e.g. "helloworld"
    }

    public function testUpdateUser()
    {
        // + Update a user account
    }

    public function testUpdateOtherUser()
    {
        // - Update someone elses account
    }

    public function testUpdateOtherUserAsAdmin()
    {
        // + Update some elses account as an admin
    }

    public function testUpdateUserDuplicateData()
    {
        // - Update a user with duplicate username
    }

    public function testUpdateUserNoAuth()
    {
        // - Update a user without providing authentication
    }
}
