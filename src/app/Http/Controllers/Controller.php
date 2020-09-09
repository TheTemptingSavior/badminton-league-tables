<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Returns a JSON Web Token to the requester
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithToken($token)
    {
        // TODO: Figure out a way to state when the token will expire
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 3600
        ], 200);
    }
}
