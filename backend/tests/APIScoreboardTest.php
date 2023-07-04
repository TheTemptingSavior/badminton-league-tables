<?php

use App\Helpers\ScoreboardHelper;
use App\Models\Scorecard;
use App\Models\Season;
use App\Models\SeasonTeams;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Testing\DatabaseMigrations;

class APIScoreboardTest extends TestCase
{
    use DatabaseMigrations;

    function testCreateNewScorecard()
    {
        // Import the existing season data
        $this->importData();
        $seasons = Season::all();
        foreach($seasons as $season) {
            $ret = ScoreboardHelper::calculateScoreboard($season->id);
            $this->assertTrue($ret);
        }

        // Make an admin user for us
        $user = User::factory()->admin()->create();

        // Create a new scorecard through the API
        $seasonId = 1;
        $scorecard = Array(
            'home_team' => 5,  // Stamford Badminton A
            'away_team' => 6,  // Stamford Badminton B
            'date_played' => '2015-11-07',  // Season ID 1
            'home_points' => 5,
            'away_points' => 4
        );

        $this->actingAs($user)
            ->json('POST', '/api/scorecards', $scorecard)
            ->seeStatusCode(201);

        // Update the scoreboard manually as the job is queued but
        // hasn't been run
        $ret = ScoreboardHelper::calculateScoreboard($seasonId);
        $this->assertTrue($ret);

        // The new scoreboard data
        $correctData = Array(
            Array("melton-mowbray", 10, 17, 8, 2, 67, 23),
            Array("stamford-badminton-a", 11, 19, 9, 2, 75, 24),
            Array("stamford-badminton-b", 11, 9, 2, 9, 37, 62),
            Array("stamford-community", 10, 8, 4, 6, 29, 61),
            Array("rockingham", 10, 3, 1, 9, 20, 70),
            Array("uppingham", 10, 14, 7, 3, 51, 39)
        );

        foreach($correctData as $data) {
            $team = DB::table('teams')->where('slug', '=', $data[0])->first();
            $this->assertNotNull($team, "Could not find team for $data[0]");

            $row = DB::table('scoreboards')
                ->where('team', '=', $team->id)
                ->where('season', '=', $seasonId)
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

    function testDeletingScorecard()
    {
        // Import the existing season data
        $this->importData();

        $seasons = Season::all();
        foreach($seasons as $season) {
            $ret = ScoreboardHelper::calculateScoreboard($season->id);
            $this->assertTrue($ret);
        }

        // Make an admin user for us
        $user = User::factory()->admin()->create();

        // Create a new scorecard through the API
        $seasonId = 1;  // 2015-12-10
        $homeTeam = 5;  // Stamford Badminton A
        $awayTeam = 6;  // Stamford Badminton B
        $scorecard = DB::table('scorecards')
            ->where('home_team', '=', $homeTeam)
            ->where('away_team', '=', $awayTeam)
            ->where('date_played', '=', '2015-12-10')
            ->first();
        $this->actingAs($user)
            ->json('DELETE', '/api/scorecards/'.$scorecard->id)
            ->seeStatusCode(204);

        // Update the scoreboard manually as the job is queued but
        // hasn't been run
        $ret = ScoreboardHelper::calculateScoreboard($seasonId);
        $this->assertTrue($ret);

        // The new scoreboard data
        $correctData = Array(
            Array("melton-mowbray", 10, 17, 8, 2, 67, 23),
            Array("stamford-badminton-a", 9, 15, 7, 2, 62, 19),
            Array("stamford-badminton-b", 9, 8, 2, 7, 32, 49),
            Array("stamford-community", 10, 8, 4, 6, 29, 61),
            Array("rockingham", 10, 3, 1, 9, 20, 70),
            Array("uppingham", 10, 14, 7, 3, 51, 39)
        );

        foreach($correctData as $data) {
            $team = DB::table('teams')->where('slug', '=', $data[0])->first();
            $this->assertNotNull($team, "Could not find team for $data[0]");

            $row = DB::table('scoreboards')
                ->where('team', '=', $team->id)
                ->where('season', '=', $seasonId)
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

    function testUpdatingScorecard()
    {
        // Import the existing season data
        $this->importData();
        $seasons = Season::all();
        foreach($seasons as $season) {
            $ret = ScoreboardHelper::calculateScoreboard($season->id);
            $this->assertTrue($ret);
        }

        // Update an existing game
        $seasonId = 1;
        $data = Array(
            'home_team' => 5,
            'away_team' => 6,
            'date_played' => '2015-12-10',
            'home_points' => 1,
            'away_points' => 8
        );
        $scorecard = DB::table('scorecards')
            ->where('home_team', '=', $data['home_team'])
            ->where('away_team', '=', $data['away_team'])
            ->where('date_played', '=', $data['date_played'])
            ->first();
        $this->assertNotNull(
            $scorecard,
            'Scorecard not found for team #'.$data['home_team'].' vs #'.$data['away_team']
        );

        // Make an admin user for us
        $user = User::factory()->admin()->create();
        $this->actingAs($user)
            ->json('PUT', '/api/scorecards/'.$scorecard->id, $data)
            ->seeStatusCode(201);

        // Update the scoreboard manually as the job is queued but
        // hasn't been run

        $ret = ScoreboardHelper::calculateScoreboard($seasonId);
        $this->assertTrue($ret);

        // Check the updated scoreboard
        $correctData = Array(
            Array("melton-mowbray", 10, 17, 8, 2, 67, 23),
            Array("stamford-badminton-a", 10, 15, 7, 3, 63, 27),
            Array("stamford-badminton-b", 10, 10, 3, 7, 40, 50),
            Array("stamford-community", 10, 8, 4, 6, 29, 61),
            Array("rockingham", 10, 3, 1, 9, 20, 70),
            Array("uppingham", 10, 14, 7, 3, 51, 39)
        );

        foreach($correctData as $data) {
            $team = DB::table('teams')->where('slug', '=', $data[0])->first();
            $this->assertNotNull($team, "Could not find team for $data[0]");

            $row = DB::table('scoreboards')
                ->where('team', '=', $team->id)
                ->where('season', '=', $seasonId)
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

    function testPartialScoreboard()
    {
        // Create the season
        $season = Season::factory()->dates('2010-09-01', '2011-08-31')->create();

        // Create the teams
        $team1 = Team::factory()->create();
        $team2 = Team::factory()->create();
        $team3 = Team::factory()->create();
        $team4 = Team::factory()->create();

        // Add the teams to the season
        SeasonTeams::factory()->season($season->id)->team($team1->id)->create();
        SeasonTeams::factory()->season($season->id)->team($team2->id)->create();
        SeasonTeams::factory()->season($season->id)->team($team3->id)->create();
        SeasonTeams::factory()->season($season->id)->team($team4->id)->create();

        // Create some scorecards (missing out team 3)
        Scorecard::factory()->setHomeTeam($team1)->setAwayTeam($team2)->setDatePlayed('2010-10-01')->create();
        Scorecard::factory()->setHomeTeam($team2)->setAwayTeam($team1)->setDatePlayed('2010-11-01')->create();
        Scorecard::factory()->setHomeTeam($team1)->setAwayTeam($team4)->setDatePlayed('2010-10-01')->create();

        // Check the scoreboard calculator worked
        $ret = ScoreboardHelper::calculateScoreboard($season->id);
        $this->assertTrue($ret);

        // Ensure we have all 4 teams in the scoreboard
        $scoreboard = DB::table('scoreboards')->where('season', '=', $season->id)->get();
        $this->assertEquals(4, $scoreboard->count(), 'Not enough teams in the scoreboard');
        $this->assertNotNull(
            DB::table('scoreboards')
                ->where('season', '=', $season->id)
                ->where('team', '=', $team1->id)
                ->first()
        );
        $this->assertNotNull(
            DB::table('scoreboards')
                ->where('season', '=', $season->id)
                ->where('team', '=', $team2->id)
                ->first()
        );
        $this->assertNotNull(
            DB::table('scoreboards')
                ->where('season', '=', $season->id)
                ->where('team', '=', $team3->id)
                ->first()
        );
        $this->assertNotNull(
            DB::table('scoreboards')
                ->where('season', '=', $season->id)
                ->where('team', '=', $team4->id)
                ->first()
        );
    }
}
