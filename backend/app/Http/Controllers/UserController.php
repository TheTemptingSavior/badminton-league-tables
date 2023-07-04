<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="List all users",
     *     description="List all users that have an account with the site",
     *     tags={"users"},
     *     security={"jwt_auth": ""},
     *     @OA\Parameter(
     *         name="page",
     *         in="path",
     *         description="Page of results to retrieve",
     *         required=false,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="path",
     *         description="Number of results to retrieve per page",
     *         required=false,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns the users in the system",
     *         @OA\JsonContent(
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="first_page_url", type="string", format="url"),
     *             @OA\Property(property="from", type="integer"),
     *             @OA\Property(property="next_page_url", type="string", format="url"),
     *             @OA\Property(property="path", type="string", format="url"),
     *             @OA\Property(property="per_page", type="integer"),
     *             @OA\Property(property="prev_page_url", type="string", format="url"),
     *             @OA\Property(property="to", type="string", format="int64"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/User")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized to access users",
     *         @OA\JsonContent(ref="#/components/schemas/UnauthorizedError")
     *     )
     * )
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listUsers(Request $request)
    {
        $per_page = $request->get('per_page', 15);
        return response()->json(User::simplePaginate($per_page));
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Get single user",
     *     description="Returns information about the user identified by their ID",
     *     tags={"users"},
     *     security={"jwt_auth": ""},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns the user object with given ID",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized to access user information",
     *         @OA\JsonContent(ref="#/components/schemas/UnauthorizedError")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Requested user does not exist",
     *         @OA\JsonContent(ref="#/components/schemas/NotFoundError")
     *     )
     * )
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser(string $id)
    {
        $user = User::findOrFail($id);
        return response()->json($user, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     summary="Create new user",
     *     description="Create a new user. It should be noted that any newly created user is NOT an admin.",
     *     tags={"users"},
     *     security={"jwt_auth": ""},
     *     @OA\RequestBody(
     *         required=true,
     *         description="New user information",
     *         @OA\JsonContent(
     *             @OA\Property(property="username", type="string"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="User created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Incorrect data provided",
     *         @OA\JsonContent(ref="#/components/schemas/BadRequestError")
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized to create new users",
     *         @OA\JsonContent(ref="#/components/schemas/UnauthorizedError")
     *     ),
     *     @OA\Response(
     *         response="409",
     *         description="There was a conflict when attempting to create the user",
     *         @OA\JsonContent(ref="#/components/schemas/ConflictError")
     *     )
     * )
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createUser(Request $request)
    {
        // Ensure the user attempting to make the account is an admin
        if (! auth()->user()->admin) {
            Log::error('User is not an admin');
            Log::error(auth()->user());
            return response()->json(['error' => 'Only an admin may create user accounts'], 403);
        }

        $this->validate($request, ['username' => 'required|unique:users', 'password' => 'required',]);
        $newUser = new User;
        $newUser->username = $request->username;
        $newUser->password = Hash::make($request->password);
        $newUser->admin = false;
        $newUser->save();

        return response()->json($newUser, 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Delete a user",
     *     description="Delete a user with the given ID. This action can only be performed be an admin. Returns no response on a successful delete",
     *     tags={"users"},
     *     security={"jwt_auth": ""},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user to delete",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="User deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad user ID provided",
     *         @OA\JsonContent(ref="#/components/schemas/BadRequestError")
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized to delete this resource",
     *         @OA\JsonContent(ref="#/components/schemas/UnauthorizedError")
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Only admins can delete users",
     *         @OA\JsonContent(ref="#/components/schemas/ForbiddenError")
     *     )
     * )
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUser(string $id)
    {
        User::findOrFail($id)->delete();
        return response()->json([], 204);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Update a user",
     *     description="Update an existing user based upon their ID",
     *     tags={"users"},
     *     security={"jwt_auth": ""},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user to update",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\RequestBody(
     *         description="Information to update for the user",
     *         @OA\JsonContent(
     *             @OA\Property(property="username", type="string"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Updated user object",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad data provided",
     *         @OA\JsonContent(ref="#/components/schemas/BadRequestError")
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized to perform this action",
     *         @OA\JsonContent(ref="#/components/schemas/UnauthorizedError")
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Access denied to editing this user",
     *         @OA\JsonContent(ref="#/components/schemas/ForbiddenError")
     *     ),
     *     @OA\Response(
     *         response="409",
     *         description="Conflict in updating the user",
     *         @OA\JsonContent(ref="#/components/schemas/ConflictError")
     *     )
     * )
     *
     * @param string $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateUser(string $id, Request $request)
    {
        $currentUser = auth()->user();
        if (! $currentUser->admin) {
            if ($currentUser->id != $id) {
                return response()->json(['error' => 'Only admins can edit other users'], 403);
            }
        }
        $this->validate($request, ['username' => 'nullable|unique:users', 'password' => 'nullable']);
        $user = User::findOrFail($id);
        $user->update($request->all());

        return response()->json($user, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}/admin",
     *     summary="Change user admin status",
     *     description="Change a users admin status",
     *     tags={"users"},
     *     security={"jwt_auth": ""},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user to update",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\RequestBody(
     *         description="Information to update for the user",
     *         @OA\JsonContent(
     *             @OA\Property(property="admin", type="boolean"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successfully changed the users admin status",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad data provided",
     *         @OA\JsonContent(ref="#/components/schemas/BadRequestError")
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized to perform this action",
     *         @OA\JsonContent(ref="#/components/schemas/UnauthorizedError")
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Only admins can change the admin status of other users",
     *         @OA\JsonContent(ref="#/components/schemas/ForbiddenError")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="User with the given ID could not be found",
     *         @OA\JsonContent(ref="#/components/schemas/NotFoundError")
     *     ),
     * )
     * @param string $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function makeAdmin(string $id, Request $request)
    {
        $this->validate(
            $request,
            ['admin' => 'required|boolean']
        );

        $user = User::findOrFail($id);
        $user->admin = $request->admin;
        $user->save();

        return response()->json($user, 200);
    }
}
