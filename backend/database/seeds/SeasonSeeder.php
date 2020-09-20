<?php

use App\Models\Season;
use Illuminate\Database\Seeder;

class SeasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $s1 = new Season;
        $s1->id = 1;
        $s1->start = '2017-09-01';
        $s1->end = '2018-08-31';
        $s1->slug = '17-18';
        $s1->save();

        $s2 = new Season;
        $s2->id = 2;
        $s2->start = '2018-09-01';
        $s2->end = '2019-08-31';
        $s2->slug = '18-19';
        $s2->save();
    }
}
