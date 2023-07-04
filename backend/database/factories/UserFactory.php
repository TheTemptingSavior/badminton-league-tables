<?php

namespace Database\Factories;


use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{

    protected $model = User::class;

    public function definition()
    {
        return [
            'username' => $this->faker->userName,
            'password' => Hash::make($this->faker->password),
            'admin' => false,
        ];
    }

    public function admin()
    {
        return $this->state([
            'admin' => true
        ]);
    }
}
