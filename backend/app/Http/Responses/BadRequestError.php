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
    public string $message;

    /**
     * @OA\Property(@OA\Items(type="string"))
     * @var array
     */
    public array $errors;
}
