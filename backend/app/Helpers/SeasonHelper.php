<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class SeasonHelper
{
    /**
     * Takes a string date object and determines which season the
     * date is in. The returned value is the slug of the season
     * @param string $datetime Date in the format of 'Y-m-d'
     * @return int ID of the season
     */
    public static function getSeasonFromDate(string $datetime): int
    {
        $season = DB::table('seasons')
            ->where('start', '<', $datetime)
            ->where('end', '>', $datetime)
            ->first();
        return ($season !== null) ? $season->id : -1;
    }

    /**
     * Returns the current season
     * @return \App\Models\Season
     */
    public static function getCurrent()
    {
        // Get the seasons ordered by date and select the first one
        // This will be the latest season in the league
        $season = DB::table('seasons')
            ->orderBy('start', 'desc')
            ->first();

        return $season;
    }
}
