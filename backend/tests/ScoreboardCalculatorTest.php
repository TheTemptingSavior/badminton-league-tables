<?php

use App\Helpers\ScoreboardHelper;
use App\Models\Season;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Testing\DatabaseMigrations;

class ScoreboardCalculatorTest extends TestCase
{
    use DatabaseMigrations;

    function testSeason2015_2016()
    {
        // It is assumed this works
        $this->artisan('import');

        $season = Season::findOrFail(1, '*');
        $ret = ScoreboardHelper::calculateScoreboard($season->id);
        $this->assertTrue($ret);

        $correctData = Array(
            Array("melton-mowbray", 10, 17, 8, 2, 67, 23),
            Array("stamford-badminton-a", 10, 17, 8, 2, 70, 20),
            Array("stamford-badminton-b", 10, 8, 2, 8, 33, 57),
            Array("stamford-community", 10, 8, 4, 6, 29, 61),
            Array("rockingham", 10, 3, 1, 9, 20, 70),
            Array("uppingham", 10, 14, 7, 3, 51, 39)
        );

        foreach($correctData as $data) {
            $team = DB::table('teams')->where('slug', '=', $data[0])->first();
            $this->assertNotNull($team, "Could not find team for $data[0]");

            $row = DB::table('scoreboards')
                ->where('team', '=', $team->id)
                ->where('season', '=', $season->id)
                ->first();
            $this->assertNotNull($row, "Could not find scoreboard data for $team->slug");
            $this->assertEquals($data[1], $row->played, "$team->slug has incorrect played value");
            $this->assertEquals($data[2], $row->points, "$team->slug has incorrect points value");
            $this->assertEquals($data[3], $row->wins, "$team->slug has incorrect wins value");
            $this->assertEquals($data[4], $row->losses, "$team->slug has incorrect losses value");
            $this->assertEquals($data[5], $row->for, "$team->slug has incorrect for value");
            $this->assertEquals($data[6], $row->against, "$team->slug has incorrect against value");
        }
    }

    function testSeason2016_2017()
    {
        // It is assumed this works
        $this->artisan('import');

        $season = Season::findOrFail(2, '*');
        $ret = ScoreboardHelper::calculateScoreboard($season->id);
        $this->assertTrue($ret);

        $correctData = Array(
            Array("melton-mowbray", 10, 19, 9, 1, 72, 18),
            Array("stamford-badminton-a", 10, 14, 7, 3, 54, 36),
            Array("stamford-badminton-b", 10, 4, 1, 9, 21, 69),
            Array("stamford-community", 9, 6, 2, 7, 23, 58),
            Array("rockingham", 9, 10, 4, 5, 40, 41),
            Array("uppingham", 10, 13, 6, 4, 51, 39)
        );

        foreach($correctData as $data) {
            $team = DB::table('teams')->where('slug', '=', $data[0])->first();
            $this->assertNotNull($team, "Could not find team for $data[0]");

            $row = DB::table('scoreboards')
                ->where('team', '=', $team->id)
                ->where('season', '=', $season->id)
                ->first();
            $this->assertNotNull($row, "Could not find scoreboard data for $team->slug");
            $this->assertEquals($data[1], $row->played, "$team->slug has incorrect played value");
            $this->assertEquals($data[2], $row->points, "$team->slug has incorrect points value");
            $this->assertEquals($data[3], $row->wins, "$team->slug has incorrect wins value");
            $this->assertEquals($data[4], $row->losses, "$team->slug has incorrect losses value");
            $this->assertEquals($data[5], $row->for, "$team->slug has incorrect for value");
            $this->assertEquals($data[6], $row->against, "$team->slug has incorrect against value");
        }
    }

    function testSeason2017_2018()
    {
        // It is assumed this works
        $this->artisan('import');

        $season = Season::findOrFail(3, '*');
        $ret = ScoreboardHelper::calculateScoreboard($season->id);
        $this->assertTrue($ret);

        $correctData = Array(
            Array("melton-mowbray", 10, 17, 8, 2, 59, 31),
            Array("stamford-badminton-a", 10, 18, 9, 1, 56, 34),
            Array("stamford-badminton-b", 10, 2, 0, 10, 19, 71),
            Array("stamford-community", 10, 10, 3, 7, 41, 49),
            Array("rockingham", 10, 10, 4, 6, 47, 43),
            Array("uppingham", 10, 13, 6, 4, 48, 42)
        );

        foreach($correctData as $data) {
            $team = DB::table('teams')->where('slug', '=', $data[0])->first();
            $this->assertNotNull($team, "Could not find team for $data[0]");

            $row = DB::table('scoreboards')
                ->where('team', '=', $team->id)
                ->where('season', '=', $season->id)
                ->first();
            $this->assertNotNull($row, "Could not find scoreboard data for $team->slug");
            $this->assertEquals($data[1], $row->played, "$team->slug has incorrect played value");
            $this->assertEquals($data[2], $row->points, "$team->slug has incorrect points value");
            $this->assertEquals($data[3], $row->wins, "$team->slug has incorrect wins value");
            $this->assertEquals($data[4], $row->losses, "$team->slug has incorrect losses value");
            $this->assertEquals($data[5], $row->for, "$team->slug has incorrect for value");
            $this->assertEquals($data[6], $row->against, "$team->slug has incorrect against value");
        }
    }

    function testSeason2018_2019()
    {
        // It is assumed this works
        $this->artisan('import');

        $season = Season::findOrFail(3, '*');
        $ret = ScoreboardHelper::calculateScoreboard($season->id);
        $this->assertTrue($ret);

        $correctData = Array(
            Array("melton-mowbray", 10, 19, 9, 1, 63, 27),
            Array("stamford-badminton-a", 10, 18, 8, 2, 68, 22),
            Array("stamford-badminton-b", 9, 1, 0, 9, 12, 69),
            Array("stamford-community", 10, 8, 3, 7, 38, 52),
            Array("rockingham", 8, 6, 2, 6, 26, 46),
            Array("uppingham", 9, 13, 6, 3, 45, 36)
        );

        foreach($correctData as $data) {
            $team = DB::table('teams')->where('slug', '=', $data[0])->first();
            $this->assertNotNull($team, "Could not find team for $data[0]");

            $row = DB::table('scoreboards')
                ->where('team', '=', $team->id)
                ->where('season', '=', $season->id)
                ->first();
            $this->assertNotNull($row, "Could not find scoreboard data for $team->slug");
            $this->assertEquals($data[1], $row->played, "$team->slug has incorrect played value");
            $this->assertEquals($data[2], $row->points, "$team->slug has incorrect points value");
            $this->assertEquals($data[3], $row->wins, "$team->slug has incorrect wins value");
            $this->assertEquals($data[4], $row->losses, "$team->slug has incorrect losses value");
            $this->assertEquals($data[5], $row->for, "$team->slug has incorrect for value");
            $this->assertEquals($data[6], $row->against, "$team->slug has incorrect against value");
        }
    }

    function testSeason2019_2020()
    {
        // It is assumed this works
        $this->artisan('import');

        $season = Season::findOrFail(3, '*');
        $ret = ScoreboardHelper::calculateScoreboard($season->id);
        $this->assertTrue($ret);

        $correctData = Array(
            Array("meltonshire-a", 6, 12, 6, 0, 49, 5),
            Array("meltonshire-b", 6, 10, 5, 1, 34, 20),
            Array("melton-mowbray", 7, 8, 3, 4, 29, 34),
            Array("stamford-badminton-a", 4, 8, 4, 0, 23, 13),
            Array("stamford-badminton-b", 2, 0, 0, 2, 3, 15),
            Array("stamford-community", 6, 3, 1, 5, 14, 40),
            Array("rockingham", 8, 7, 2, 6, 32, 40),
            Array("uppingham", 5, 3, 1, 4, 14, 31)
        );

        foreach($correctData as $data) {
            $team = DB::table('teams')->where('slug', '=', $data[0])->first();
            $this->assertNotNull($team, "Could not find team for $data[0]");

            $row = DB::table('scoreboards')
                ->where('team', '=', $team->id)
                ->where('season', '=', $season->id)
                ->first();
            $this->assertNotNull($row, "Could not find scoreboard data for $team->slug");
            $this->assertEquals($data[1], $row->played, "$team->slug has incorrect played value");
            $this->assertEquals($data[2], $row->points, "$team->slug has incorrect points value");
            $this->assertEquals($data[3], $row->wins, "$team->slug has incorrect wins value");
            $this->assertEquals($data[4], $row->losses, "$team->slug has incorrect losses value");
            $this->assertEquals($data[5], $row->for, "$team->slug has incorrect for value");
            $this->assertEquals($data[6], $row->against, "$team->slug has incorrect against value");
        }
    }
}
