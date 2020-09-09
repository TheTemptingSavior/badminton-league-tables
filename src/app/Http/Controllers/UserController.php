<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * List all users that have an account with the site
     * @return \Illuminate\Http\JsonResponse
     */
    public function listUsers()
    {
        return response()->json(User::all());
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
        $this->validate($request, [
            'username' => 'nullable|unique:users',
            'password' => 'nullable'
        ]);
        $user = User::findOrFail($id);
        $user->update($request->all());

        return response()->json($user, 200);
    }
}
