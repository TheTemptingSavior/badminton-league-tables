<?php

use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Testing\DatabaseMigrations;

class APIRegistrarTest extends TestCase
{
    use DatabaseMigrations;

    function testCreateRegistrar()
    {
        $this->json(
            'POST',
            '/api/registrar',
            ['email' => 'test@example.com']
        )
        ->seeStatusCode(201)
        ->seeJsonStructure(['email', 'token']);
    }

    function testCreateRegistrarDatabaseCheck()
    {
        $testEmail = 'test@example.com';
        $this->json(
            'POST',
            '/api/registrar',
            ['email' => $testEmail]
        )
        ->seeStatusCode(201)
        ->seeJsonStructure(['email', 'token']);

        $registrar = DB::table('registrars')
            ->orderBy('email', 'desc')
            ->select(['*'])
            ->where('email', '=', $testEmail)
            ->first();
        $this->assertNotNull($registrar);
        $this->assertEquals($registrar->email, $testEmail);
        $this->assertNotNull($registrar->token);
        $this->assertEquals(strlen($registrar->token), 128);
    }

    function testCreateRegistrarDuplicateEmail()
    {
        $testEmail = 'test@example.com';
        $this->json(
            'POST',
            '/api/registrar',
            ['email' => $testEmail]
        )
        ->seeStatusCode(201)
        ->seeJsonStructure(['email']);

        $this->json(
            'POST',
            '/api/registrar',
            ['email' => $testEmail]
        )
        ->seeStatusCode(409)
        ->seeJsonStructure(['errors']);
    }

    function testCreateRegistrarNoEmail()
    {
        $response = $this->json(
            'POST',
            '/api/registrar'
        )
        ->seeStatusCode(400)
        ->seeJsonStructure(['errors']);
        $response->seeStatusCode(400);
    }
}