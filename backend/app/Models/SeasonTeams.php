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
 * @property integer $id
 * @property integer $season
 * @property integer $team
 * @property string $created_at
 * @property string $updated_at
 * @method static findOrFail(string $id)
 * @method static simplePaginate(integer $per_page)
 */
class SeasonTeams extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected string $table = 'season_teams';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected array $fillable = [
        'season', 'team',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public bool $timestamps = true;
}
