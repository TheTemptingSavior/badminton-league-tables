<?php

namespace App\Helpers;

use App\Models\Scoreboard;
use App\Models\Season;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScoreboardHelper
{
    /**
     * Recalculate the scoreboard for a specific season. This is
     * done by retrieving all the scorecards for the season and
     * checking the scores of each team and adding their respective
     * points
     * @param int $season ID of the season to recalculate
     * @return bool True if the recalculation was successful, false otherwise
     */
    public static function calculateScoreboard(int $season): bool
    {
        Log::info("Calculating scoreboard for season ".$season);
        try {
            // Sanity to check to ensure the season exists
            $seasonObject = Season::findOrFail($season, "*");
            $seasonStart = $seasonObject->start;
            $seasonEnd = $seasonObject->end;
        } catch (\Exception $exception) {
            Log::error("Could not find season '".$season."'");
            return false;
        }

        // Get all scorecards from the season
        $rawData = DB::table('scorecards')
            ->where('date_played', '>', $seasonStart)
            ->where('date_played', '<', $seasonEnd)
            ->select(['home_team', 'away_team', 'date_played', 'home_points', 'away_points'])
            ->get();

        $processedData = [];
        foreach ($rawData as $card) {
            // Check to ensure both teams on the scorecard
            if (! array_key_exists($card->home_team, $processedData)) {
                $processedData[$card->home_team] = [
                    'points' => 0,
                    'played' => 0,
                    'wins' => 0,
                    'losses' => 0,
                    'for' => 0,
                    'against' => 0,
                ];
            }
            if (! array_key_exists($card->away_team, $processedData)) {
                $processedData[$card->away_team] = [
                    'points' => 0,
                    'played' => 0,
                    'wins' => 0,
                    'losses' => 0,
                    'for' => 0,
                    'against' => 0,
                ];
            }

            // Add that each team has played another game
            $processedData[$card->home_team]['played'] += 1;
            $processedData[$card->away_team]['played'] += 1;
            // Add the for and against to each team
            $processedData[$card->home_team]['for'] += $card->home_points;
            $processedData[$card->home_team]['against'] += $card->away_points;
            $processedData[$card->away_team]['for'] += $card->away_points;
            $processedData[$card->away_team]['against'] += $card->home_points;

            // Did the home team win the match
            if ($card->away_points < $card->home_points) {
                $processedData[$card->home_team]['wins'] += 1;
                $processedData[$card->away_team]['losses'] += 1;
                // Add two points to the winner and 1 point if the away team lost 5:4
                $processedData[$card->home_team]['points'] += 2;
                $processedData[$card->away_team]['points'] += ($card->away_points == 4) ? 1 : 0;
            } else {
                $processedData[$card->away_team]['wins'] += 1;
                $processedData[$card->home_team]['losses'] += 1;
                // Add two points to the winner and 1 point if the away team lost 5:4
                $processedData[$card->away_team]['points'] += 2;
                $processedData[$card->home_team]['points'] += ($card->home_points == 4) ? 1 : 0;
            }
        }

        // Wrap this as one large transaction to ensure we have
        // no duplicate entries
        Log::info("Starting a database transaction for saving the scorecard");
        DB::beginTransaction();
        self::deleteOldData($season);
        Log::info("Old scoreboard data deleted, saving new data");
        self::saveData($processedData, $season);

        // Check to make sure there is a valid scoreboard set here
        $existing = DB::table('scoreboards')->where('season', '=', $season)->get()->count();
        if ($existing == 0) {
            self::generateZeros($seasonObject->id);
        }

        DB::commit();

        return true;
    }

    /**
     * Save the calculated scoreboard to the database
     * @param $data array data Data to save as the to the scoreboard
     * @param $season int ID of the season to save the data against
     */
    protected static function saveData(array $data, int $season)
    {
        Log::info("Saving scoreboard data");
        foreach (array_keys($data) as $key) {
            $sb = new Scoreboard;
            $sb->team = $key;
            $sb->season = $season;
            $sb->played = $data[$key]['played'];
            $sb->points = $data[$key]['points'];
            $sb->wins = $data[$key]['wins'];
            $sb->losses = $data[$key]['losses'];
            $sb->for = $data[$key]['for'];
            $sb->against = $data[$key]['against'];
            $sb->save();
        }
    }

    /**
     * Deletes calculated scoreboard for the given season
     * @param int $season ID of the season to delete scoreboards from
     */
    protected static function deleteOldData(int $season)
    {
        Log::info("Deleting old scoreboard data");
        DB::table('scoreboards')
            ->where('season', '=', $season)
            ->delete();
    }

    protected static function generateZeros($seasonId)
    {
        Log::info("No scoreboard exists for season {$seasonId}. Generating an empty one");
        $seasonTeams = DB::table('season_teams')->where('season_id', '=', $seasonId)->get();
        foreach($seasonTeams as $seasonTeam) {
            $sb = new Scoreboard;
            $sb->team = $seasonTeam->team_id;
            $sb->season = $seasonId;
            $sb->played = 0;
            $sb->points = 0;
            $sb->wins = 0;
            $sb->losses = 0;
            $sb->for = 0;
            $sb->against = 0;
            $sb->saveOrFail();
        }
    }
}
