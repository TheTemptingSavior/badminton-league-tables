<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * List all users that have an account with the site
     *
     * TODO: This needs to be paginated
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listUsers()
    {
        return response()->json(User::all());
    }

    /**
     * Returns information about the user identified by their ID
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser(string $id)
    {
        $user = User::findOrFail($id);
        return response()->json($user, 200);
    }

    /**
     * Create a new user. It should be noted that any newly created user
     * is NOT an admin. For a user to become an admin, the site owner must
     * change their admin status manually. This route requires the following
     * data: 'username' and 'password'
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createUser(Request $request)
    {
        // Ensure the user attempting to make the account is an admin
        if (!auth()->user()->admin) {
            Log::error('User is not an admin');
            Log::error(auth()->user());
            return response()->json(['error' => 'Only an admin may create user accounts'], 403);
        }

        $this->validate(
            $request,
            [
                'username' => 'required|unique:users',
                'password' => 'required',
            ]
        );
        $newUser = new User;
        $newUser->username = $request->username;
        $newUser->password = Hash::make($request->password);
        $newUser->admin = false;
        $newUser->save();

        return response()->json($newUser, 201);
    }

    /**
     * Delete a user with the given ID. This action can only be performed by
     * an admin. Returns no response on a successful delete
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUser(string $id)
    {
        User::findOrFail($id)->delete();
        return response()->json([], 204);
    }

    /**
     * Update an existing user based upon their ID
     * @param string $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateUser(string $id, Request $request)
    {
        $currentUser = auth()->user();
        if (!$currentUser->admin) {
            if ($currentUser->id != $id) {
                return response()->json(['error' => 'Only admins can edit other users'], 403);
            }
        }
        $this->validate($request, [
            'username' => 'nullable|unique:users',
            'password' => 'nullable'
        ]);
        $user = User::findOrFail($id);
        $user->update($request->all());

        return response()->json($user, 200);
    }
}
