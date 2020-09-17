<?php


namespace App\Http\Responses;


/**
 * @OA\Schema(
 *     title="ForbiddenError",
 *     description="Generic access denied error response"
 * )
 */
class ForbiddenError
{
    /**
     * @OA\Property()
     * @var string
     */
    public $message;
}
