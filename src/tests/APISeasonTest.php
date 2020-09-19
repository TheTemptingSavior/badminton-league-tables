<?php

use App\Models\Season;
use Laravel\Lumen\Testing\DatabaseMigrations;

class APISeasonTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Create two example seasons that are stored in the database
     * @return array Season IDs
     */
    function createSeasons()
    {
        $season17_18 = new Season;
        $season17_18->start = strtotime("2017-09-01");
        $season17_18->end = strtotime("2018-08-31");
        $season17_18->slug = "17-18";
        $season17_18->save();

        $season18_19 = new Season;
        $season18_19->start = strtotime("2018-09-01");
        $season18_19->end = strtotime("2019-08-31");
        $season18_19->slug = "18-19";
        $season18_19->save();

        return [
            $season17_18->id,
            $season18_19->id,
        ];
    }

    /**
     * List the available seasons. We can use the factory here as
     * we are not concerned about the content returned just that
     * the correct content was returned from the server
     *
     * @return void
     */
    function testListSeasons()
    {
        factory('App\Models\Season', 3)->create();

        $result = $this->json('GET', '/api/seasons')
            ->seeStatusCode(200);

        $this->assertCount(3, json_decode($result->response->content()));
    }

    /**
     * Get a specific season from the list
     *
     * @return void
     */
    function testGetSeason()
    {
        $ids = $this->createSeasons();

        $result = $this->json('GET', '/api/seasons/' . $ids[0])
            ->seeStatusCode(200)
            ->seeJson(['id' => $ids[0]])
            ->seeJsonStructure(['id', 'start', 'end', 'slug']);

        $data = json_decode($result->response->content());
        $this->assertEquals(
            strtotime("2017-09-1"),
            $data->start
        );
    }

    /**
     * Attempt to retrieve a season that doesn't exist
     *
     * @return void
     */
    function testGetSeasonNonExist()
    {
        $this->json('GET', '/api/seasons/999')
            ->seeStatusCode(404);
    }

    /**
     * Attempt to get a season with a bad ID
     *
     * @return  void
     */
    function testGetSeasonBadId()
    {
        $this->json('GET', '/api/seasons/helloworld')
            ->seeStatusCode(404);
    }

    /**
     * Retrieve a season object from its slug
     *
     * @return void
     */
    function testGetSeasonFromSlug()
    {
        $season = factory('App\Models\Season')->create();
        $this->json('GET', '/api/seasons/fromslug/' . $season->slug)
            ->seeJsonStructure(['id', 'start', 'end', 'slug'])
            ->seeJson(['slug' => $season->slug])
            ->seeStatusCode(200);
    }
}