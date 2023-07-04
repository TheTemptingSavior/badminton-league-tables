<?php


use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\TeamSeeder;
use Database\Seeders\SeasonSeeder;
use Database\Seeders\SeasonTeamsSeeder;
use Database\Seeders\ScorecardSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            TeamSeeder::class,
            SeasonSeeder::class,
            SeasonTeamsSeeder::class,
            ScorecardSeeder::class,
        ]);
    }
}
