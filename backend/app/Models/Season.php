<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Season",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64"
 *     ),
 *     @OA\Property(
 *         property="start",
 *         type="string",
 *         format="datetime"
 *     ),
 *     @OA\Property(
 *         property="end",
 *         type="string",
 *         format="datetime"
 *     ),
 *     @OA\Property(
 *         property="slug",
 *         type="string"
 *     ),
 *     @OA\Property(
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
 * @property string $start
 * @property string $end
 * @property string $slug
 * @property string $created_at
 * @property string $updated_at
 * @method static findOrFail(string $id, string $column)
 * @method static simplePaginate(integer $per_page)
 */
class Season extends Model
{
    use HasFactory;

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
    protected $table = 'seasons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'start', 'end', 'slug'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $casts = [
        'start' => 'date', 
        'end' => 'date',
    ];
}
