<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /*
    * List all users that have an account with the site
    */
    public function listUsers()
    {
        return response()->json(User::all());
    }

    /*
     * Create a new user. This route requires the following data:
     *  - username
     *  - password
     *  - admin
     */
    public function createUser(Request $request)
    {
        $newUser = User::create($request->all());

        return response()->json($newUser, 201);
    }

    /*
     * Delete a user with the given ID
     */
    public function deleteUser(integer $id)
    {
        User::findOrFail($id)->delete();
        return response('Deleted Successfully', 204);
    }
}
