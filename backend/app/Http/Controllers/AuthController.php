<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Returns a JSON Web Token to the requester
     * @param $token Token to create a response with
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithToken($token): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => TOKEN_EXPIRATION
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="User login",
     *     description="Get a JWT via given credentials",
     *     tags={"auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Login credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="username", type="string"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successfull login",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="token_type", type="string"),
     *             @OA\Property(property="expires_in", type="integer", format="int64")
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad data provided",
     *         @OA\JsonContent(ref="#/components/schemas/BadRequestError")
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Incorrect login details provided",
     *         @OA\JsonContent(ref="#/components/schemas/UnauthorizedError")
     *     ),
     * )
     *
     * @param Request $request Lumen request object
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $credentials = $request->only(['username', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    /**
     * @OA\Get(
     *     path="/api/auth/logout",
     *     summary="User logout",
     *     description="Adds the users current token to a black list forcing them to re-login to the application",
     *     tags={"auth"},
     *     security={"jwt_auth": ""},
     *     @OA\Response(
     *         response="204",
     *         description="Successfull logout operation",
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Bearer authentication required to access this route",
     *     )
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): \Illuminate\Http\JsonResponse
    {
        // TODO: Add the token to a blacklist here
        return response()->json(['message'=>'Goodbye!'], 200);

    }

    /**
     * @OA\Get(
     *     path="/api/auth/me",
     *     summary="Current user info",
     *     tags={"auth"},
     *     description="Get current user details identified by the bearer token used",
     *     security={"jwt_auth": ""},
     *     @OA\Response(
     *         response="200",
     *         description="Identifies the user of the JSON web token used",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Bearer authentication required to access this route",
     *     )
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(): \Illuminate\Http\JsonResponse
    {
        return response()->json(auth()->user());
    }
}
