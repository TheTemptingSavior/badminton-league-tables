<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'username' => $this->faker->unique()->name,
            'password' => Hash::make($this->faker->password),
            'super_admin' => false,
            'admin' => false,
        ];
    }

    public function admin()
    {
        return $this->state([
            'admin' => true,
        ]);
    }
}
