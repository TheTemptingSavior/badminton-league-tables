<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Registrar",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
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
 * @property string $email
 * @property string $created_at
 * @property string $updated_at
 * @method static findOrFail(string $id)
 * @method static simplePaginate(integer $per_page)
 */
class Registrar extends Model
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
    protected $table = 'registrars';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'eail',
    ];
}
