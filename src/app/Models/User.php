<?php

namespace App\Models;


use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 * @OA\Schema
 * @package App\Models
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable;

    /**
     * @OA\Property()
     * @var string
     */
    public $username;
    /**
     * @OA\Property(
     *     type="string",
     *     format="password"
     * )
     * @var string
     */
    public $password;
    /**
     * @OA\Property()
     * @var boolean
     */
    public $admin;
    /**
     * @OA\Property(
     *     type="string",
     *     format="date-time"
     * )
     * @var string
     */
    public $added;
    /**
     * @OA\Property(
     *     type="string",
     *     format="date-time"
     * )
     * @var string
     */
    public $created_at;
    /**
     * @OA\Property(
     *     type="string",
     *     format="date-time"
     * )
     * @var string
     */
    public $updated_at;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'admin' => false,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'added', 'admin',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
