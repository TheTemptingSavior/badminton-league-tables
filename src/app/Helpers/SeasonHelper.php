<?php

class SeasonHelper
{
    /**
     * Takes a string date object and determines which season the
     * date is in. The returned value is the slug of the season
     * @param string $datetime
     * @return string Slug of the season
     * @throws Throwable
     */
    public static function getSeasonFromDate(string $datetime)
    {
        $slug = "";
        // Extract the year and month from the datetime
        $year = 2019;
        $month = 10;

        // Basic checks
        throw_if(
            ($month <= 1 or 12 <= $month),
            \Illuminate\Validation\ValidationException::withMessages(["Month must be an integer between 1 and 12"])
        );
        throw_if(
            (($year - 200) <= 0),
            \Illuminate\Validation\ValidationException::withMessages(["Only years above 2000 are currently supported"])
        );

        if ($month <= 9) {
            $slug = "18-19";
            $slug = ($year-2001)."-".($year-2000);
        } else {
            $slug = ($year-2000)."-".($year-1999);
        }

       return $slug;
    }
}
