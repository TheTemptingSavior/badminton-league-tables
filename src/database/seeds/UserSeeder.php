<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("users")->insert([
            "username" => "ethan",
            "password" => Hash::make("cotterell"),
            "admin" => true,
            "created_at" => date("Y-m-d H:i:s"),
        ]);
    }
}
