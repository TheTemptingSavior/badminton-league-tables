<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    use HasFactory;

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
        'season_id', 'team_id',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
}
