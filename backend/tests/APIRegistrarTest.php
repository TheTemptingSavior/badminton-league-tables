<?php

use App\Models\Registrar;
use App\Models\User;
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
        $this->json(
            'POST',
            '/api/registrar'
        )
        ->seeStatusCode(400)
        ->seeJsonStructure(['errors']);
    }

    function testGetRegistrarsNoAuth()
    {
        $this->json('GET', '/api/registrar')
            ->seeStatusCode(401);
    }

    function testGetRegistrars()
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user)
            ->json('GET', '/api/registrar')
            ->seeJsonStructure(
                ['current_page', 'first_page_url', 'from', 'next_page_url', 'path', 'per_page', 'prev_page_url', 'to']
            )
            ->seeStatusCode(200);
    }

    function testGetReigistrarsNoAdmin()
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->json('GET', '/api/registrar')
            ->seeStatusCode(403);
    }

    function testGetRegistrarsWithContent()
    {
        Registrar::factory()->count(10)->create();

        $user = User::factory()->admin()->create();
        $result = $this->actingAs($user)
            ->json('GET', '/api/registrar')
            ->seeJsonStructure(
                ['current_page', 'first_page_url', 'from', 'next_page_url', 'path', 'per_page', 'prev_page_url', 'to']
            )
            ->seeStatusCode(200);
        $data = json_decode($result->response->content());
        $this->assertEquals(1, $data->current_page);
        $this->assertCount(10, $data->data);
        $this->assertStringEndsWith('/api/registrar?page=1', $data->first_page_url);
        $this->assertEquals(1, $data->from);
        $this->assertNull($data->next_page_url);
        $this->assertStringEndsWith('/api/registrar', $data->path);
        $this->assertEquals(15, $data->per_page);
        $this->assertNull($data->prev_page_url);
        $this->assertEquals(10, $data->to);
    }

    function testGetRegistrarsMoreThanOnePage()
    {
        Registrar::factory()->count(50)->create();

        $user = User::factory()->admin()->create();
        $result = $this->actingAs($user)
            ->json('GET', '/api/registrar')
            ->seeJsonStructure(
                ['current_page', 'first_page_url', 'from', 'next_page_url', 'path', 'per_page', 'prev_page_url', 'to']
            )
            ->seeStatusCode(200);
        $data = json_decode($result->response->content());
        $this->assertEquals(1, $data->current_page);
        $this->assertCount(15, $data->data);
        $this->assertStringEndsWith('/api/registrar?page=1', $data->first_page_url);
        $this->assertEquals(1, $data->from);
        $this->assertStringEndsWith('/api/registrar?page=2', $data->next_page_url);
        $this->assertStringEndsWith('/api/registrar', $data->path);
        $this->assertEquals(15, $data->per_page);
        $this->assertNull($data->prev_page_url);
        $this->assertEquals(15, $data->to);
    }

    function testGetRegistrarsMiddlePage()
    {
        Registrar::factory()->count(50)->create();

        $user = User::factory()->admin()->create();
        $result = $this->actingAs($user)
            ->json('GET', '/api/registrar?page=3')
            ->seeJsonStructure(
                ['current_page', 'first_page_url', 'from', 'next_page_url', 'path', 'per_page', 'prev_page_url', 'to']
            )
            ->seeStatusCode(200);
        $data = json_decode($result->response->content());
        $this->assertEquals(3, $data->current_page);
        $this->assertCount(15, $data->data);
        $this->assertStringEndsWith('/api/registrar?page=1', $data->first_page_url);
        $this->assertEquals(31, $data->from);
        $this->assertStringEndsWith('/api/registrar?page=4', $data->next_page_url);
        $this->assertStringEndsWith('/api/registrar', $data->path);
        $this->assertEquals(15, $data->per_page);
        $this->assertStringEndsWith('/api/registrar?page=2', $data->prev_page_url);
        $this->assertEquals(45, $data->to);
    }

    function testGetRegistrarsPageLimit()
    {
        Registrar::factory()->count(50)->create();

        $user = User::factory()->admin()->create();
        $result = $this->actingAs($user)
            ->json('GET', '/api/registrar?per_page=30')
            ->seeJsonStructure(
                ['current_page', 'first_page_url', 'from', 'next_page_url', 'path', 'per_page', 'prev_page_url', 'to']
            )
            ->seeStatusCode(200);
        $data = json_decode($result->response->content());
        $this->assertEquals(1, $data->current_page);
        $this->assertCount(30, $data->data);
        $this->assertStringEndsWith('/api/registrar?page=1', $data->first_page_url);
        $this->assertEquals(1, $data->from);
        $this->assertStringEndsWith('/api/registrar?page=2', $data->next_page_url);
        $this->assertStringEndsWith('/api/registrar', $data->path);
        $this->assertEquals(30, $data->per_page);
        $this->assertNull($data->prev_page_url);
        $this->assertEquals(30, $data->to);
    }
}