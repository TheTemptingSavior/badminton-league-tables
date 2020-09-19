<?php


namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @OA\Info(
     *     title="League Tables API",
     *     version="0.1.0",
     *     description="Provides access to league table information via JSON APIs",
     *
     *     @OA\Contact(
     *         email="ethancotterell@gmail.com",
     *         name="Ethan Cotterell"
     *     ),
     *     @OA\License(
     *         name="Apache 2.0",
     *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *     ),
     *     @OA\Server(
     *         description="Localhost server",
     *         url="http://localhost:8080/api"
     *     )
     * )
     */

    /**
     * @OA\SecurityScheme(
     *     securityScheme="bearerAuth",
     *     type="http",
     *     scheme="bearer",
     *     name="jwt_auth",
     *     bearerFormat="JWT"
     * )
     */
}
