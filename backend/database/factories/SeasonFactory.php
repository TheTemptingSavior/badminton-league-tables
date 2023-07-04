<?php

namespace Database\Factories;

use App\Models\Season;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeasonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Season::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $startYear = $this->faker->unique()->numberBetween(2001, 2100);
        $endYear = $startYear + 1;
        $startDate = $startYear.'-09-01';
        $endDate = $endYear.'-08-31';

        return [
            'start' => $startDate,
            'end' => $endDate,
            'slug' => substr($startYear, 2).'-'.substr($endYear, 2),
        ];
    }

    public function dates($start, $end)
    {
        return $this->state([
            'start' => $start,
            'end' => $end,
        ]);
    }
}
