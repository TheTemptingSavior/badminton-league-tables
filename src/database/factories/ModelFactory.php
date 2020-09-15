<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Season;
use App\Models\Team;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

// Bulk create standard, non-admin, users
$factory->define(User::class, function (Faker $faker) {
    return [
        'username' => $faker->userName,
        'password' => Hash::make($faker->password),
    ];
});

// Bulk create admin users
$factory->state(User::class, 'admin', function ($faker) {
    return [
        'username' => $faker->userName,
        'password' => Hash::make($faker->password),
        'admin' => true,
    ];
});

// TODO: Pick a random date and convert it to a valid season
$factory->define(Season::class, function (Faker $faker) {
    $season_date = $faker->dateTimeThisDecade;

    return [
        'start' => $faker->dateTimeThisCentury(),
        'end' => $faker->dateTimeThisCentury(),
        'slug' => $faker->slug
    ];
});


$factory->define(Team::class, function (Faker $faker) {
    return [
        'name' => $faker->userName,
        'slug' => $faker->slug,
    ];
});
