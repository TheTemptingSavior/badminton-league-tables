<?php


namespace App\Http\Responses;

/**
 * Class ConflictError
 * @package App\Http\Responses
 * @OA\Schema(
 *     title="ConflictError",
 *     description="Generic database conflict error response",
 * )
 */
class ConflictError
{
    /**
     * @OA\Property()
     * @var string
     */
    public $message;
}
