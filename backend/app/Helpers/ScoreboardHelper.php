<?php

namespace App\Helpers;

use App\Models\Scoreboard;
use App\Models\Scorecard;
use App\Models\Season;
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
    public static function calculateScoreboard(int $season)
    {
        Log::info("Calculating scoreboard for season " . $season);
        $seasonStart = null;
        $seasonEnd = null;
        try {
            // Sanity to check to ensure the season exists
            $seasonObject = Season::findOrFail($season);
            $seasonStart = $seasonObject->start;
            $seasonEnd = $seasonObject->end;
        } catch (Exception $exception) {
            return false;
        }

        // Get all scorecards from the season
        $rawData = DB::table('scorecards')
            ->where('date_played', '>', date('Y-m-d', $seasonStart))
            ->where('date_played', '<', date('Y-m-d', $seasonEnd))
            ->select(['home_team', 'away_team', 'date_played', 'home_points', 'away_points'])
            ->get();
        print_r($rawData);
        $processedData = [];
        foreach ($rawData as $card) {
            // Check to ensure both teams on the scorecard
            if (! array_key_exists($card->home_team, $processedData)) {
                $processedData[$card->home_team] = ['points' => 0, 'played' => 0, 'wins' => 0, 'losses' => 0, 'for' => 0, 'against' => 0];
            }
            if (! array_key_exists($card->away_team, $processedData)) {
                $processedData[$card->away_team] = ['points' => 0, 'played' => 0, 'wins' => 0, 'losses' => 0, 'for' => 0, 'against' => 0];
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
        DB::commit();

        return true;
    }

    /**
     * Save the calculated scoreboard to the database
     * @param $data Scoreboard data
     * @param $season Season ID
     */
    protected static function saveData($data, $season)
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

    protected static function deleteOldData(int $season)
    {
        Log::info("Deleting old scoreboard data");
        $data = DB::table('scoreboards')
            ->where('season', '=', $season)
            ->get();
        foreach($data as $d) {
            $d->delete();
        }
    }
}
