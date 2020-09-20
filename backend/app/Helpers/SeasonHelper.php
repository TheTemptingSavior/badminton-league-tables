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
    public static function getSeasonFromDate(string $datetime)
    {
        $season = DB::table('seasons')
            ->where('start', '<', $datetime)
            ->where('end', '>', $datetime)
            ->first();

       return $season->id;
    }
}
