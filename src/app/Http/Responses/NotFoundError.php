<?php


namespace App\Http;

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
    public $message;
}
