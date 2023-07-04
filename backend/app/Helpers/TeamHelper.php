<?php

namespace App\Helpers;


use App\Models\Season;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeamHelper
{
    public static function canRetire($teamId, $seasonId)
    {
        $season = Season::findOrFail($seasonId);
        $scorecards = DB::table('scorecards')
            ->where('date_played', '>', $season->start)
            ->where('date_played', '<', $season->end)
            ->where(function($query) use ($teamId) {
                $query->where('home_team', '=', $teamId)
                    ->orWhere('away_team', '=', $teamId);
            })->select(['home_team', 'away_team', 'date_played', 'date_played'])
            ->get();

        if (sizeof($scorecards) === 0) {
            // There are no scorecards
            return true;
        } else {
            Log::debug('Team has existing scorecards');
            Log::debug($scorecards);
            return false;
        }
    }
}
