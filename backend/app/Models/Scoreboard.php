<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Scoreboard",
 *     @OA\Property(
 *         property="season",
 *         type="integer",
 *         format="int64"
 *     ),
 *     @OA\Property(
 *         property="slug",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(
 *             @OA\Property(
 *                 property="id",
 *                 type="integer",
 *                 format="int64"
 *             ),
 *             @OA\Property(
 *                 property="team",
 *                 type="integer",
 *                 format="int64"
 *             ),
 *             @OA\Property(
 *                 property="season",
 *                 type="integer",
 *                 format="int64"
 *             ),
 *             @OA\Property(
 *                 property="played",
 *                 type="integer",
 *                 format="int64"
 *             ),
 *             @OA\Property(
 *                 property="points",
 *                 type="integer",
 *                 format="int64"
 *             ),
 *             @OA\Property(
 *                 property="wins",
 *                 type="integer",
 *                 format="int64"
 *             ),
 *             @OA\Property(
 *                 property="losses",
 *                 type="integer",
 *                 format="int64"
 *             ),
 *             @OA\Property(
 *                 property="for",
 *                 type="integer",
 *                 format="int64"
 *             ),
 *             @OA\Property(
 *                 property="against",
 *                 type="integer",
 *                 format="int64"
 *             ),
 *             @OA\Property(
 *                 property="created_at",
 *                 type="string",
 *                 format="datetime"
 *             ),
 *             @OA\Property(
 *                 property="updated_at",
 *                 type="string",
 *                 format="datetime"
 *             )
 *         )
 *     )
 * )
 * @property integer $team
 * @property integer $season
 * @property integer $played
 * @property integer $points
 * @property integer $wins
 * @property integer $losses
 * @property integer $for
 * @property integer $against
 * @property string $created_at
 * @property string $updated_at
 * @method static findOrFail(string $id)
 * @method static simplePaginate(integer $per_page)
 */
class Scoreboard extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public bool $timestamps = true;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected string $table = 'scoreboards';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected array $fillable = [
        'team', 'season', 'played', 'points', 'wins', 'losses', 'for', 'against'
    ];
}
