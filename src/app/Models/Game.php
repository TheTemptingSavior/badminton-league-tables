<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    /**
     * Returns a set of validation rules to be run against all the
     * required data to create a game. This includes the teams that
     * played, the date the match was played and the number of games
     * each team one.
     * @return string[]
     */
    public static function getValidationRules()
    {
        return [
            // Must be an ID in the teams table and different from one another
            'home_team' => 'required|exists:teams,id|different:away_team',
            'away_team' => 'required|exists:teams,id|different:home_team',
            // Must be a date of format YYYY-MM-DD not in the future
            'date_played' => 'required|date|date_format:Y-m-d|before_or_equal:now',
            // Must be numeric, different from one another and between 0 and 9
            'home_points' => 'required|numeric|different:away_points|between:0,9',
            'away_points' => 'required|numeric|different:home_points|between:0,9',
            // Can be null or a string with between 0 and 200 characters
            'home_player_1' => 'string|nullable|digits_between:1,200',
            'home_player_2' => 'string|nullable|digits_between:1,200',
            'home_player_3' => 'string|nullable|digits_between:1,200',
            'home_player_4' => 'string|nullable|digits_between:1,200',
            'home_player_5' => 'string|nullable|digits_between:1,200',
            'home_player_6' => 'string|nullable|digits_between:1,200',
            'away_player_1' => 'string|nullable|digits_between:1,200',
            'away_player_2' => 'string|nullable|digits_between:1,200',
            'away_player_3' => 'string|nullable|digits_between:1,200',
            'away_player_4' => 'string|nullable|digits_between:1,200',
            'away_player_5' => 'string|nullable|digits_between:1,200',
            'away_player_6' => 'string|nullable|digits_between:1,200',
            // Can be null or a numeric between 0 and 30
            'game_one_v_one_game_home_one' => 'numeric|nullable|between:0,30',
            'game_one_v_one_game_away_one' => 'numeric|nullable|between:0,30',
            'game_one_v_one_game_home_two' => 'numeric|nullable|between:0,30',
            'game_one_v_one_game_away_two' => 'numeric|nullable|between:0,30',
            'game_one_v_one_game_home_three' => 'numeric|nullable|between:0,30',
            'game_one_v_one_game_away_three' => 'numeric|nullable|between:0,30',
            'game_one_v_two_game_home_one' => 'numeric|nullable|between:0,30',
            'game_one_v_two_game_away_one' => 'numeric|nullable|between:0,30',
            'game_one_v_two_game_home_two' => 'numeric|nullable|between:0,30',
            'game_one_v_two_game_away_two' => 'numeric|nullable|between:0,30',
            'game_one_v_two_game_home_three' => 'numeric|nullable|between:0,30',
            'game_one_v_two_game_away_three' => 'numeric|nullable|between:0,30',
            'game_one_v_three_game_home_one' => 'numeric|nullable|between:0,30',
            'game_one_v_three_game_away_one' => 'numeric|nullable|between:0,30',
            'game_one_v_three_game_home_two' => 'numeric|nullable|between:0,30',
            'game_one_v_three_game_away_two' => 'numeric|nullable|between:0,30',
            'game_one_v_three_game_home_three' => 'numeric|nullable|between:0,30',
            'game_one_v_three_game_away_three' => 'numeric|nullable|between:0,30',

            'game_two_v_one_game_home_one' => 'numeric|nullable|between:0,30',
            'game_two_v_one_game_away_one' => 'numeric|nullable|between:0,30',
            'game_two_v_one_game_home_two' => 'numeric|nullable|between:0,30',
            'game_two_v_one_game_away_two' => 'numeric|nullable|between:0,30',
            'game_two_v_one_game_home_three' => 'numeric|nullable|between:0,30',
            'game_two_v_one_game_away_three' => 'numeric|nullable|between:0,30',
            'game_two_v_two_game_home_one' => 'numeric|nullable|between:0,30',
            'game_two_v_two_game_away_one' => 'numeric|nullable|between:0,30',
            'game_two_v_two_game_home_two' => 'numeric|nullable|between:0,30',
            'game_two_v_two_game_away_two' => 'numeric|nullable|between:0,30',
            'game_two_v_two_game_home_three' => 'numeric|nullable|between:0,30',
            'game_two_v_two_game_away_three' => 'numeric|nullable|between:0,30',
            'game_two_v_three_game_home_one' => 'numeric|nullable|between:0,30',
            'game_two_v_three_game_away_one' => 'numeric|nullable|between:0,30',
            'game_two_v_three_game_home_two' => 'numeric|nullable|between:0,30',
            'game_two_v_three_game_away_two' => 'numeric|nullable|between:0,30',
            'game_two_v_three_game_home_three' => 'numeric|nullable|between:0,30',
            'game_two_v_three_game_away_three' => 'numeric|nullable|between:0,30',

            'game_three_v_one_game_home_one' => 'numeric|nullable|between:0,30',
            'game_three_v_one_game_away_one' => 'numeric|nullable|between:0,30',
            'game_three_v_one_game_home_two' => 'numeric|nullable|between:0,30',
            'game_three_v_one_game_away_two' => 'numeric|nullable|between:0,30',
            'game_three_v_one_game_home_three' => 'numeric|nullable|between:0,30',
            'game_three_v_one_game_away_three' => 'numeric|nullable|between:0,30',
            'game_three_v_two_game_home_one' => 'numeric|nullable|between:0,30',
            'game_three_v_two_game_away_one' => 'numeric|nullable|between:0,30',
            'game_three_v_two_game_home_two' => 'numeric|nullable|between:0,30',
            'game_three_v_two_game_away_two' => 'numeric|nullable|between:0,30',
            'game_three_v_two_game_home_three' => 'numeric|nullable|between:0,30',
            'game_three_v_two_game_away_three' => 'numeric|nullable|between:0,30',
            'game_three_v_three_game_home_one' => 'numeric|nullable|between:0,30',
            'game_three_v_three_game_away_one' => 'numeric|nullable|between:0,30',
            'game_three_v_three_game_home_two' => 'numeric|nullable|between:0,30',
            'game_three_v_three_game_away_two' => 'numeric|nullable|between:0,30',
            'game_three_v_three_game_home_three' => 'numeric|nullable|between:0,30',
            'game_three_v_three_game_away_three' => 'numeric|nullable|between:0,30',
        ];
    }

    /**
     * Takes an array of game data and runs additional validation against
     * the required attributes
     * @param array $data
     * @return array|bool True if the data passes validation else an array of error messages
     */
    public function validateData(array $data)
    {
        $errors = [];
        if ($data['home_team'] == $data['away_team']) {
            array_push($errors, 'Home team and away team cannot be the same');
        }

        if (date('Y-m-d') <= $data['date_played']) {
            array_push($errors, 'The date played field cannot be in the future');
        }

        if (($data['home_points'] + $data['away_points']) != 9) {
            array_push($errors, 'The total played games (home points + away points) must total 9');
        }

        if (sizeof($errors) == 0) {
            return true;
        } else {
            return $errors;
        }
    }
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'games';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
