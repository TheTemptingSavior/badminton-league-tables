<?php

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
        ->seeJsonStructure(['email']);
    }
}