<?php


namespace Database\Factories;


use App\Models\Scorecard;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScorecardFactory extends Factory
{
    /**
     * Name of the factories model
     *
     * @var string
     */
    protected $model = Scorecard::class;

    /**
     * Model's default state
     * @return array|void
     */
    public function definition()
    {
        $home = Team::all()->random()->id;
        $away = Team::all()->random()->id;
        while ($home == $away) {
            $away = Team::all()->random()->id;
        }
        $points = $this->faker->numberBetween(0, 9);
        return [
            'home_team' => $home,
            'away_team' => $away,
            'date_played' => $this->faker->date('Y-m-d'),
            'home_points' => $points,
            'away_points' => (9 - $points),
            'home_player_1' => ($this->faker->boolean ? $this->faker->name : null),
            'home_player_2' => ($this->faker->boolean ? $this->faker->name : null),
            'home_player_3' => ($this->faker->boolean ? $this->faker->name : null),
            'home_player_4' => ($this->faker->boolean ? $this->faker->name : null),
            'home_player_5' => ($this->faker->boolean ? $this->faker->name : null),
            'home_player_6' => ($this->faker->boolean ? $this->faker->name : null),
            'away_player_1' => ($this->faker->boolean ? $this->faker->name : null),
            'away_player_2' => ($this->faker->boolean ? $this->faker->name : null),
            'away_player_3' => ($this->faker->boolean ? $this->faker->name : null),
            'away_player_4' => ($this->faker->boolean ? $this->faker->name : null),
            'away_player_5' => ($this->faker->boolean ? $this->faker->name : null),
            'away_player_6' => ($this->faker->boolean ? $this->faker->name : null),
        ];
    }
}
