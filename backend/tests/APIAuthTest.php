<?php

use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Log;

class APIAuthTest extends TestCase
{
    use DatabaseMigrations;

    function testUserLogin()
    {
        // Must manually create a user as otherwise the password
        // is hashed and inaccessible
        $user = new \App\Models\User;
        $user->username = "test-username";
        $user->password = Hash::make('helloworld');
        $user->admin = false;
        $user->save();

        $result = $this->json(
                'POST',
                '/api/auth/login',
                ['username' => 'test-username', 'password' => 'helloworld']
            )
            ->seeStatusCode(200)
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
