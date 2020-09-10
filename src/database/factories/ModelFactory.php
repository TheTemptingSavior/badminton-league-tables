<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;

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
        'password' => $faker->password,
    ];
});

// Bulk create admin users
$factory->state(User::class, 'admin', function ($faker) {
    return [
        'username' => $faker->userName,
        'password' => $faker->password,
        'admin' => true,
    ];
});
