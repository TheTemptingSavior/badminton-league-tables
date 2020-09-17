<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="SeasonTeam",
 *     @OA\Property(
 *         property="id",
 *         type="numeric",
 *         format="int64"
 *     ),
 *     @OA\Property(
 *         property="season",
 *         type="numeric",
 *         format="int64"
 *     ),
 *     @OA\Property(
 *         property="team",
 *         type="numeric",
 *         format="int64"
 *     )
 * )
 */
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
