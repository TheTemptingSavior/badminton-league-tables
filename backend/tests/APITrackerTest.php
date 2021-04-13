<?php


use App\Models\Scorecard;
use App\Models\Season;
use App\Models\SeasonTeams;
use App\Models\Team;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class APITrackerTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    function populateSeasonTeams($season, $teams)
    {
        foreach($teams as $team) {
            SeasonTeams::factory()
                ->season($season->id)
                ->team($team->id)
                ->create();
        }
    }

    function testCalculateTracker()
    {
        $season = Season::factory()->create();
        $datePlayed = $season->start;
        $teams = Team::factory()->count(6)->create();

        $this->populateSeasonTeams($season, $teams);

        // Create some phony scorecards
        Scorecard::factory()->count(1)
            ->setHomeTeam($teams[0]->id)
            ->setAwayTeam($teams[1]->id)
            ->setDatePlayed($datePlayed)
            ->create();
        Scorecard::factory()->count(1)
            ->setHomeTeam($teams[1]->id)
            ->setAwayTeam($teams[2]->id)
            ->setDatePlayed($datePlayed)
            ->create();
        Scorecard::factory()->count(1)
            ->setHomeTeam($teams[2]->id)
            ->setAwayTeam($teams[3]->id)
            ->setDatePlayed($datePlayed)
            ->create();
        Scorecard::factory()->count(1)
            ->setHomeTeam($teams[3]->id)
            ->setAwayTeam($teams[4]->id)
            ->setDatePlayed($datePlayed)
            ->create();
        Scorecard::factory()->count(1)
            ->setHomeTeam($teams[4]->id)
            ->setAwayTeam($teams[5]->id)
            ->setDatePlayed($datePlayed)
            ->create();

        $result = $this->json('GET', '/api/tracker/current')
            ->seeJsonStructure(
                ['season', 'data']
            )
            ->seeStatusCode(200);
        $data = json_decode($result->response->content());

        $this->assertEquals($season->id, $data->season->id);
        // All the  teams exist so now we can validate the actual scorecard
        foreach ($teams as $team) {
            $t = $team->id;
            $dt = $data->data->$t;
            $this->assertNotNull($dt);
            $this->assertEquals($dt->id, $team->id);
        }
    }
}
