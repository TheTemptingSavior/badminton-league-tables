<?php


namespace Database\Factories;


use App\Models\Season;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeasonFactory extends Factory
{
    protected $model = Season::class;

    public function definition()
    {
        $seasonDate = $this->faker->dateTimeThisDecade();
        $dateObject = new DateTime($seasonDate->format('Y-m-d'));

        $startYear = $dateObject->format('Y');
        $endYear = $dateObject->modify('+1 year')->format('Y');
        $startDate = $startYear.'-09-01';
        $endDate = $endYear.'-08-31';

        return [
            'start' => $startDate,
            'end' => $endDate,
            'slug' => substr($startYear, 2).'-'.substr($endYear, 2),
        ];
    }
}
