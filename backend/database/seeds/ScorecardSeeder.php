<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScorecardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a complete season

        // All the games for home team 1
        DB::table('scorecards')->insert(['home_team' => 1, 'away_team' => 2, 'date_played' => '2017-11-12', 'home_points' => 6, 'away_points' => 3]);
        DB::table('scorecards')->insert(['home_team' => 1, 'away_team' => 3, 'date_played' => '2017-11-26', 'home_points' => 4, 'away_points' => 5]);
        DB::table('scorecards')->insert(['home_team' => 1, 'away_team' => 4, 'date_played' => '2017-12-01', 'home_points' => 7, 'away_points' => 2]);

        DB::table('scorecards')->insert(['home_team' => 2, 'away_team' => 1, 'date_played' => '2017-12-04', 'home_points' => 1, 'away_points' => 8]);
        DB::table('scorecards')->insert(['home_team' => 2, 'away_team' => 3, 'date_played' => '2018-01-04', 'home_points' => 4, 'away_points' => 5]);
        DB::table('scorecards')->insert(['home_team' => 2, 'away_team' => 4, 'date_played' => '2018-01-10', 'home_points' => 5, 'away_points' => 4]);

        DB::table('scorecards')->insert(['home_team' => 3, 'away_team' => 1, 'date_played' => '2018-01-14', 'home_points' => 4, 'away_points' => 5]);
        DB::table('scorecards')->insert(['home_team' => 3, 'away_team' => 2, 'date_played' => '2018-01-28', 'home_points' => 6, 'away_points' => 3]);
        DB::table('scorecards')->insert(['home_team' => 3, 'away_team' => 4, 'date_played' => '2018-02-02', 'home_points' => 2, 'away_points' => 7]);

        DB::table('scorecards')->insert(['home_team' => 4, 'away_team' => 1, 'date_played' => '2018-02-18', 'home_points' => 8, 'away_points' => 1]);
        DB::table('scorecards')->insert(['home_team' => 4, 'away_team' => 2, 'date_played' => '2018-03-04', 'home_points' => 6, 'away_points' => 3]);
        DB::table('scorecards')->insert(['home_team' => 4, 'away_team' => 3, 'date_played' => '2018-04-10', 'home_points' => 3, 'away_points' => 6]);
    }
}
