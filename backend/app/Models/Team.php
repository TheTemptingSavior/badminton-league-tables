<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Team",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *     ),
 *     @OA\Property(
 *         property="slug",
 *         type="string",
 *     ),
 *     @OA\Property(
 *         property="retired",
 *         type="boolean"
 *     ),
 *     @Oa\Property(
 *         property="created_at",
 *         type="string",
 *         format="datetime"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="datetime"
 *     )
 * )
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property boolean $retired
 * @property string $created_at
 * @property string $updated_at
 * @method static findOrFail(string $id)
 * @method static simplePaginate(integer $per_page)
 */
class Team extends Model
{
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
    protected $table = 'teams';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'retired'
    ];
}
