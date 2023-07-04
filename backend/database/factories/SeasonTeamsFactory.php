<?php


namespace Database\Factories;


use App\Models\SeasonTeams;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeasonTeamsFactory extends Factory
{
    protected $model = SeasonTeams::class;

    public function definition()
    {
        return [
            'season_id' => null,
            'team_id' => null
        ];
    }

    public function season($id)
    {
        return $this->state([
            'season_id' => $id
        ]);
    }

    public function team($id)
    {
        return $this->state([
            'team_id' => $id
        ]);
    }
}
