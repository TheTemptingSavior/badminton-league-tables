<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("teams")->insert([
            "id" => 1,
            "name" => "Stamford Badminton A",
            "slug" => "stamford-badminton-a",
            "created_at" => date("Y-m-d H:i:s"),
        ]);
        DB::table("teams")->insert([
            "id" => 2,
            "name" => "Stamford Badminton B",
            "slug" => "stamford-badminton-b",
            "created_at" => date("Y-m-d H:i:s"),
        ]);
        DB::table("teams")->insert([
            "id" => 3,
            "name" => "Stamford Community",
            "slug" => "stamford-community",
            "created_at" => date("Y-m-d H:i:s"),
        ]);
        DB::table("teams")->insert([
            "id" => 4,
            "name" => "Mars",
            "slug" => "mars",
            "created_at" => date("Y-m-d H:i:s"),
        ]);
    }
}
