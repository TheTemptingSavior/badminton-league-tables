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
     * Get user details.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }
}
