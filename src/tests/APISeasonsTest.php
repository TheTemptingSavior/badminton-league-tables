<?php


class APISeasonsTest extends TestCase
{
    /**
     * List all the seasons present
     */
    public function testListSeasons()
    {
        factory('App\Models\Season', 5)->make();
        $result = $this->json('GET', '/api/seasons')
            ->seeStatusCode(200);
        $teams = json_decode($result->response->content());
        $this->assertCount(5, $teams, "Expected 5 seasons.");
    }
}
