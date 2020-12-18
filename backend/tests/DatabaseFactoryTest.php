<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class DatabaseFactoryTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Tests the creation of 10 seasons using the database factory method. Checks
     * to ensure that the months and day of each timestamp are correct. Then it
     * ensures that the years are consecutive
     * @throws Exception Date time exception from an invalid format
     */
    function testSeasonGenerate()
    {
        for($i = 0; $i < 10; $i++) {
            $season = factory('App\Models\Season', 1)->make()->first();
            $startDate = new DateTime($season->start);
            $endDate = new DateTime($season->end);
            $startYear = intval($startDate->format('Y'));
            $endYear = intval($endDate->format('Y'));

            $this->assertNotNull($season);
            $this->assertEquals("09", $startDate->format('m'));
            $this->assertEquals("01", $startDate->format('d'));
            $this->assertEquals("08", $endDate->format('m'));
            $this->assertEquals("31", $endDate->format('d'));
            $this->assertEquals($startYear + 1, $endYear);
        }
    }
}
