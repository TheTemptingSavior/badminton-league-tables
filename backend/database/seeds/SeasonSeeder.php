<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('seasons')->insert([
            "id" => 1,
            'start' => strtotime('2017-09-01'),
            'end' => strtotime('2018-08-31'),
            'slug' => '17-18',
        ]);
        DB::table('seasons')->insert([
            "id" => 2,
            'start' => strtotime('2018-09-01'),
            'end' => strtotime('2019-08-31'),
            'slug' => '18-19',
        ]);
    }
}
