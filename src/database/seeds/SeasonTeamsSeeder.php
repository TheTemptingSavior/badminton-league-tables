<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeasonTeamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('season_teams')->insert(['season_id' => 1, 'team_id' => 1,]);
        DB::table('season_teams')->insert(['season_id' => 1, 'team_id' => 2,]);
        DB::table('season_teams')->insert(['season_id' => 1, 'team_id' => 3,]);
        DB::table('season_teams')->insert(['season_id' => 1, 'team_id' => 4,]);

        DB::table('season_teams')->insert(['season_id' => 2, 'team_id' => 1,]);
        DB::table('season_teams')->insert(['season_id' => 2, 'team_id' => 2,]);
        DB::table('season_teams')->insert(['season_id' => 2, 'team_id' => 3,]);
    }
}
