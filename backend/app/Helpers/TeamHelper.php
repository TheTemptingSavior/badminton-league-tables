<?php

namespace App\Helpers;


use App\Models\Season;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeamHelper
{
    public static function canRetire($teamId, $seasonId)
    {
        Log::debug("Checking if team #{$teamId} can retire from season #$seasonId");
        $season = Season::findOrFail($seasonId);
        $scorecards = DB::table('scorecards')
            ->where('date_played', '>', $season->start)
            ->where('date_played', '<', $season->end)
            ->where(function($query) use ($teamId) {
                $query->where('home_team', '=', $teamId)
                    ->orWhere('away_team', '=', $teamId);
            })->select(['home_team', 'away_team', 'date_played', 'date_played'])
            ->get()
            ->toArray();

        if (sizeof($scorecards) == 0) {
            // There are no scorecards
            Log::debug("Team has no existing scorecards in the season");
            return true;
        } else {
            $number = sizeof($scorecards);
            Log::debug("Team has #{$number} existing scorecards");
            return false;
        }
    }
}
