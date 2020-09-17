<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        // TODO: change this to match /api/auth/login, /api/auth/logout and (post) /api/users
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

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

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        $credentials = $request->only(['username', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    /**
     * Adds the users current token to a black list forcing them to
     * re-login to the application
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        // TODO: Add the token to a blacklist here
        return response()->json(['message'=>'Goodbye!'], 200);

    }

    /**
     * @OA\Get(
     *     path="/api/auth/me",
     *     tags={"auth"},
     *     description="Get current user details",
     *     @OA\Response(
     *         response=200,
     *         description="Identifies the user of the JSON web token used",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Bearer authentication required to access this route",
     *     )
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }
}
