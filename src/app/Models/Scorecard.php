<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scorecard extends Model
{
    protected static $EMPTY_SCORECARD = [
        'home_team' => null, 'away_team' => null, 'date_played' => null, 'home_points' => null, 'away_points' => null,
        'home_player_1' => null, 'home_player_2' => null, 'home_player_3' => null, 'home_player_4' => null,
        'home_player_5' => null, 'home_player_6' => null, 'away_player_1' => null, 'away_player_2' => null,
        'away_player_3' => null, 'away_player_4' => null, 'away_player_5' => null, 'away_player_6' => null,
        'game_one_v_one_home_one' => null, 'game_one_v_one_away_one' => null, 'game_one_v_one_home_two' => null,
        'game_one_v_one_away_two' => null, 'game_one_v_one_home_three' => null, 'game_one_v_one_away_three' => null,
        'game_one_v_two_home_one' => null, 'game_one_v_two_away_one' => null, 'game_one_v_two_home_two' => null,
        'game_one_v_two_away_two' => null, 'game_one_v_two_home_three' => null, 'game_one_v_two_away_three' => null,
        'game_one_v_three_home_one' => null, 'game_one_v_three_away_one' => null, 'game_one_v_three_home_two' => null,
        'game_one_v_three_away_two' => null, 'game_one_v_three_home_three' => null,
        'game_one_v_three_away_three' => null, 'game_two_v_one_home_one' => null, 'game_two_v_one_away_one' => null,
        'game_two_v_one_home_two' => null, 'game_two_v_one_away_two' => null, 'game_two_v_one_home_three' => null,
        'game_two_v_one_away_three' => null, 'game_two_v_two_home_one' => null, 'game_two_v_two_away_one' => null,
        'game_two_v_two_home_two' => null, 'game_two_v_two_away_two' => null, 'game_two_v_two_home_three' => null,
        'game_two_v_two_away_three' => null, 'game_two_v_three_home_one' => null, 'game_two_v_three_away_one' => null,
        'game_two_v_three_home_two' => null, 'game_two_v_three_away_two' => null, 'game_two_v_three_home_three' => null,
        'game_two_v_three_away_three' => null, 'game_three_v_one_home_one' => null, 'game_three_v_one_away_one' => null,
        'game_three_v_one_home_two' => null, 'game_three_v_one_away_two' => null, 'game_three_v_one_home_three' => null,
        'game_three_v_one_away_three' => null, 'game_three_v_two_home_one' => null, 'game_three_v_two_away_one' => null,
        'game_three_v_two_home_two' => null, 'game_three_v_two_away_two' => null, 'game_three_v_two_home_three' => null,
        'game_three_v_two_away_three' => null, 'game_three_v_three_home_one' => null,
        'game_three_v_three_away_one' => null, 'game_three_v_three_home_two' => null,
        'game_three_v_three_away_two' => null, 'game_three_v_three_home_three' => null,
        'game_three_v_three_away_three' => null,
    ];



    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;



    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'scorecards';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'home_team', 'away_team', 'date_played', 'home_points', 'away_points',
        'home_player_1', 'home_player_2', 'home_player_3', 'home_player_4', 'home_player_5', 'home_player_6',
        'away_player_1', 'away_player_2', 'away_player_3', 'away_player_4', 'away_player_5', 'away_player_6',
        'game_one_v_one_home_one', 'game_one_v_one_away_one', 'game_one_v_one_home_two', 'game_one_v_one_away_two',
        'game_one_v_one_home_three', 'game_one_v_one_away_three', 'game_one_v_two_home_one', 'game_one_v_two_away_one',
        'game_one_v_two_home_two', 'game_one_v_two_away_two', 'game_one_v_two_home_three', 'game_one_v_two_away_three',
        'game_one_v_three_home_one', 'game_one_v_three_away_one', 'game_one_v_three_home_two',
        'game_one_v_three_away_two', 'game_one_v_three_home_three', 'game_one_v_three_away_three',
        'game_two_v_one_home_one', 'game_two_v_one_away_one', 'game_two_v_one_home_two', 'game_two_v_one_away_two',
        'game_two_v_one_home_three', 'game_two_v_one_away_three', 'game_two_v_two_home_one', 'game_two_v_two_away_one',
        'game_two_v_two_home_two', 'game_two_v_two_away_two', 'game_two_v_two_home_three', 'game_two_v_two_away_three',
        'game_two_v_three_home_one', 'game_two_v_three_away_one', 'game_two_v_three_home_two',
        'game_two_v_three_away_two', 'game_two_v_three_home_three', 'game_two_v_three_away_three',
        'game_three_v_one_home_one', 'game_three_v_one_away_one', 'game_three_v_one_home_two',
        'game_three_v_one_away_two', 'game_three_v_one_home_three', 'game_three_v_one_away_three',
        'game_three_v_two_home_one', 'game_three_v_two_away_one', 'game_three_v_two_home_two',
        'game_three_v_two_away_two', 'game_three_v_two_home_three', 'game_three_v_two_away_three',
        'game_three_v_three_home_one', 'game_three_v_three_away_one', 'game_three_v_three_home_two',
        'game_three_v_three_away_two', 'game_three_v_three_home_three', 'game_three_v_three_away_three',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * Takes a scorecard and adds keys for all the missing values.
     * Any added keys default to null
     * @param array $data
     * @return array
     */
    public static function PAD_SCORECARD(array $data)
    {
        return array_merge(self::$EMPTY_SCORECARD, $data);
    }

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
            'home_player_1' => 'string|nullable|between:1,200',
            'home_player_2' => 'string|nullable|between:1,200',
            'home_player_3' => 'string|nullable|between:1,200',
            'home_player_4' => 'string|nullable|between:1,200',
            'home_player_5' => 'string|nullable|between:1,200',
            'home_player_6' => 'string|nullable|between:1,200',
            'away_player_1' => 'string|nullable|between:1,200',
            'away_player_2' => 'string|nullable|between:1,200',
            'away_player_3' => 'string|nullable|between:1,200',
            'away_player_4' => 'string|nullable|between:1,200',
            'away_player_5' => 'string|nullable|between:1,200',
            'away_player_6' => 'string|nullable|between:1,200',
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
     * the required attributes.
     * TODO: Requires separate tests
     * @param array $data
     * @return array|bool True if the data passes validation else an array of error messages
     */
    public static function validateData(array $data)
    {
        $errors = [];

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
     * Inspect the contents of the scorecard to check the data that has
     * been entered into it.
     * TODO: Requires separate tests
     * @param array $data
     * @return array Any warnings generated by the inspection
     */
    public static function checkData(array $data)
    {
        $warnings = [];
        $games = [
            // Set 1
            ['game_one_v_one_home_one', 'game_one_v_one_away_one'],
            ['game_one_v_one_home_two', 'game_one_v_one_away_two'],
            ['game_one_v_one_home_three', 'game_one_v_one_away_three'],
            // Set 2
            ['game_one_v_two_home_one', 'game_one_v_two_away_one'],
            ['game_one_v_two_home_two', 'game_one_v_two_away_two'],
            ['game_one_v_two_home_three', 'game_one_v_two_away_three'],
            // Set 3
            ['game_one_v_three_home_one', 'game_one_v_three_away_one'],
            ['game_one_v_three_home_two', 'game_one_v_three_away_two'],
            ['game_one_v_three_home_three', 'game_one_v_three_away_three'],
            // Set 4
            ['game_two_v_one_home_one', 'game_two_v_one_away_one'],
            ['game_two_v_one_home_two', 'game_two_v_one_away_two'],
            ['game_two_v_one_home_three', 'game_two_v_one_away_three'],
            // Set 5
            ['game_two_v_two_home_one', 'game_two_v_two_away_one'],
            ['game_two_v_two_home_two', 'game_two_v_two_away_two'],
            ['game_two_v_two_home_three', 'game_two_v_two_away_three'],
            // Set 6
            ['game_two_v_three_home_one', 'game_two_v_three_away_one'],
            ['game_two_v_three_home_two', 'game_two_v_three_away_two'],
            ['game_two_v_three_home_three', 'game_two_v_three_away_three'],
            // Set 7
            ['game_three_v_one_home_one', 'game_three_v_one_away_one'],
            ['game_three_v_one_home_two', 'game_three_v_one_away_two'],
            ['game_three_v_one_home_three', 'game_three_v_one_away_three'],
            // Set 8
            ['game_three_v_two_home_one', 'game_three_v_two_away_one'],
            ['game_three_v_two_home_two', 'game_three_v_two_away_two'],
            ['game_three_v_two_home_three', 'game_three_v_two_away_three'],
            // Set 9
            ['game_three_v_three_home_one', 'game_three_v_three_away_one'],
            ['game_three_v_three_home_two', 'game_three_v_three_away_two'],
            ['game_three_v_three_home_three', 'game_three_v_three_away_three'],
        ];

        foreach ($games as $game) {
            if ($data[$game[0]] == null and $data[$game[1]] != null) {
                array_push(
                    $warnings,
                    "The scores do not add up for '" . $game[0] . "' and '" . $game[1] . "'"
                );
                continue;
            } else if ($data[$game[0]] != null and $data[$game[1]] == null) {
                array_push(
                    $warnings,
                    "The scores do not add up for '" . $game[0] . "' and '" . $game[1] . "'"
                );
                continue;
            }
            // One of the values is null so ignore it
            if ($data[$game[0]] == null) { continue; }
            if ($data[$game[1]] == null) { continue; }

            // Validate the two scores against each other
            // Standard games
            if ($data[$game[0]] == 21 and $data[$game[1]] <= 19) { continue; }
            if ($data[$game[0]] <= 19 and $data[$game[1]] == 21) { continue; }
            // Max length game
            if ($data[$game[0]] == 29 and $data[$game[1]] == 30) { continue; }
            if ($data[$game[0]] == 30 and $data[$game[1]] == 29) { continue; }
            // Games over 21
            if ($data[$game[0]] == ($data[$game[1]] - 2)) { continue; }
            if ($data[$game[0]] == ($data[$game[1]] + 2)) { continue; }

            // Failed the above validations so it must be incorrect
            array_push(
                $warnings,
                "The scores do not add up for '" . $game[0] . "' and '" . $game[1] . "'");
        }

        return $warnings;
    }
}
