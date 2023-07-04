<?php


namespace App\Http\Responses;

/**
 * Class UnauthorizedError
 * @package App\Http\Responses
 * @OA\Schema(
 *     title="UnauthorizedError",
 *     description="Generic unauthorized error response",
 * )
 */
class UnauthorizedError
{
    /**
     * @OA\Property()
     * @var string
     */
    public $message;
}
