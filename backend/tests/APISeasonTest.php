<?php

use App\Models\Season;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class APISeasonTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

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
        Season::factory()->count(3)->create();

        $result = $this->json('GET', '/api/seasons')
            ->seeJsonStructure(
                ['current_page', 'first_page_url', 'from', 'next_page_url', 'path', 'per_page', 'prev_page_url', 'to']
            )
            ->seeStatusCode(200);
        $data = json_decode($result->response->content());

        $this->assertCount(3, $data->data);
        $this->assertEquals(1, $data->current_page);
        $this->assertEquals(15, $data->per_page);
        $this->assertStringEndsWith('/api/seasons?page=1', $data->first_page_url);
        $this->assertNull($data->next_page_url);
        $this->assertNull($data->prev_page_url);
        $this->assertStringEndsWith('/api/seasons', $data->path);
        $this->assertEquals(1, $data->from);
        $this->assertEquals(3, $data->to);
    }

    /**
     * Test listing seasons that require multiple pages. Get the first page and
     * assert that the meta data is correct
     *
     * @return void
     */
    function testListSeasonsPaged()
    {
        Season::factory()->count(20)->create();
        $result = $this->json('GET', '/api/seasons')
            ->seeJsonStructure(
                ['current_page', 'first_page_url', 'from', 'next_page_url', 'path', 'per_page', 'prev_page_url', 'to']
            )
            ->seeStatusCode(200);
        $data = json_decode($result->response->content());

        $this->assertCount(15, $data->data);
        $this->assertEquals(1, $data->current_page);
        $this->assertEquals(15, $data->per_page);
        $this->assertStringEndsWith('/api/seasons?page=1', $data->first_page_url);
        $this->assertStringEndsWith('/api/seasons?page=2', $data->next_page_url);
        $this->assertNull($data->prev_page_url);
        $this->assertStringEndsWith('/api/seasons', $data->path);
        $this->assertEquals(1, $data->from);
        $this->assertEquals(15, $data->to);
    }

    /**
     * List the available seasons where a second page of results is required
     *
     * @return void
     */
    function testListSeasonsPageTwo()
    {
        Season::factory()->count(40)->create();
        $result = $this->json('GET', '/api/seasons?page=2')
            ->seeJsonStructure(
                ['current_page', 'first_page_url', 'from', 'next_page_url', 'path', 'per_page', 'prev_page_url', 'to']
            )
            ->seeStatusCode(200);
        $data = json_decode($result->response->content());

        $this->assertCount(15, $data->data);
        $this->assertEquals(2, $data->current_page);
        $this->assertEquals(15, $data->per_page);
        $this->assertStringEndsWith('/api/seasons?page=1', $data->first_page_url, "Incorrect first page URL");
        $this->assertStringEndsWith('/api/seasons?page=3', $data->next_page_url, "Incorrect next page url");
        $this->assertStringEndsWith('/api/seasons?page=1', $data->prev_page_url, "Incorrect previous page URL");
        $this->assertStringEndsWith('/api/seasons', $data->path);
        $this->assertEquals(16, $data->from);
        $this->assertEquals(30, $data->to);
    }

    /**
     * List the seasons in this league and changing the number of season
     * per page
     *
     * @return void
     */
    function testListSeasonsPageLimit()
    {
        Season::factory()->count(20)->create();
        $result = $this->json('GET', '/api/seasons?per_page=10')
            ->seeJsonStructure(
                ['current_page', 'first_page_url', 'from', 'next_page_url', 'path', 'per_page', 'prev_page_url', 'to']
            )
            ->seeStatusCode(200);
        $data = json_decode($result->response->content());

        $this->assertCount(10, $data->data);
        $this->assertEquals(1, $data->current_page);
        $this->assertEquals(10, $data->per_page);
        $this->assertStringEndsWith('/api/seasons?page=1', $data->first_page_url);
        $this->assertStringEndsWith('/api/seasons?page=2', $data->next_page_url);
        $this->assertNull($data->prev_page_url);
        $this->assertStringEndsWith('/api/seasons', $data->path);
        $this->assertEquals(1, $data->from);
        $this->assertEquals(10, $data->to);
    }

    /**
     * Return a list of seasons, selecting a page from the middle of the results
     *
     * @return void
     */
    function testListSeasonsMiddlePage()
    {
        Season::factory()->count(30)->create();
        $result = $this->json('GET', '/api/seasons?per_page=10&page=2')
            ->seeJsonStructure(
                ['current_page', 'first_page_url', 'from', 'next_page_url', 'path', 'per_page', 'prev_page_url', 'to']
            )
            ->seeStatusCode(200);
        $data = json_decode($result->response->content());

        $this->assertCount(10, $data->data);
        $this->assertEquals(2, $data->current_page);
        $this->assertEquals(10, $data->per_page);
        $this->assertStringEndsWith('/api/seasons?page=1', $data->first_page_url, "Incorrect first page URL");
        $this->assertStringEndsWith('/api/seasons?page=3', $data->next_page_url, "Incorrect next page URL");
        $this->assertStringEndsWith('/api/seasons?page=1', $data->prev_page_url, "Incorrect previous page URL");
        $this->assertStringEndsWith('/api/seasons', $data->path);
        $this->assertEquals(11, $data->from);
        $this->assertEquals(20, $data->to);
    }

    /**
     * Get a specific season from the list
     *
     * @return void
     * @throws Exception
     */
    function testGetSeason()
    {
        $ids = $this->createSeasons();

        $result = $this->json('GET', '/api/seasons/' . $ids[0])
            ->seeStatusCode(200)
            ->seeJson(['id' => $ids[0]])
            ->seeJsonStructure(['id', 'start', 'end', 'slug']);

        $data = json_decode($result->response->content());
        $d = new DateTime('2017-09-01');
        $this->assertEquals(
            $d->getTimestamp(),
            (new DateTime($data->start))->getTimestamp()
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
        $season = Season::factory()->create();
        $this->json('GET', '/api/seasons/fromslug/' . $season->slug)
            ->seeJsonStructure(['id', 'start', 'end', 'slug'])
            ->seeJson(['slug' => $season->slug])
            ->seeStatusCode(200);
    }
}
