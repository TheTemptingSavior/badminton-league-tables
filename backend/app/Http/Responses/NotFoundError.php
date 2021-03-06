<?php


namespace App\Http\Responses;

/**
 * @OA\Schema(
 *     title="NotFoundError",
 *     description="Generic object not found error response"
 * )
 */
class NotFoundError
{
    /**
     * @OA\Property()
     * @var string
     */
    public string $message;
}
