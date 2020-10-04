<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class APIAuthTest extends TestCase
{
    use DatabaseMigrations;

    function testUserLogin()
    {
        $user = factory('App\Models\User')->create();

        $this->json(
                'POST',
                '/api/auth/login',
                ['username' => $user->username, 'password' => $user->password]
            )->seeStatusCode(200)
            ->seeJsonStructure(['token', 'token_type', 'expires_in']);
    }

    function testUserLoginNoData()
    {
        $this->json('POST', '/api/auth/login', [])
            ->seeStatusCode(400);
    }

    function testUserLoginIncorrect()
    {
        $this->json('POST', '/api/auth/login', ['username' => 'test', 'password' => 'password'])
            ->seeStatusCode(401);
    }
}
