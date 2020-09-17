<?php


namespace App\Http\Responses;

/**
 * Class BadRequestError
 * @package App\Http\Responses
 * @OA\Schema(
 *     title="BadRequestError",
 *     description="Generic incorrect data error response",
 * )
 */
class BadRequestError
{
    /**
     * @OA\Property()
     * @var string
     */
    public $message;
}
