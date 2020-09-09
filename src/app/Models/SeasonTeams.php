<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeasonTeams extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'season_teams';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'season', 'team',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
}
