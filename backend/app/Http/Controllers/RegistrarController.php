<?php

namespace App\Http\Controllers;

use App\Models\Registrar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegistrarController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/registrars",
     *     summary="Create new registered user to receive emails",
     *     description="Creates a new registrar within the database that is set to receive emails whenver a scorecard is added",
     *     tags={"registrars"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="New registrar information",
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email")
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Registrar created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Registrar")
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Incorrect data provided",
     *         @OA\JsonContent(ref="#/components/schemas/BadRequestError")
     *     ),
     *     @OA\Response(
     *         response="409",
     *         description="There was a conflict when attempting to create the registrar",
     *         @OA\JsonContent(ref="#/components/schemas/ConflictError")
     *     )
     * )
     * @param Request $request Lumen request object
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createRegistrar(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), ['email' => 'required|unique:registrars']);
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            if (array_key_exists('email', $errors) and strpos(implode(' ', $errors['email']), 'taken')) {
                $code = 409;
            } else {
                $code = 400;
            }
            return response()->json(['errors' => $validator->errors()], $code);
        }
        $newRegistrar = new Registrar;
        $newRegistrar->email = $request->email;
        $newRegistrar->token = Str::random(128);
        $newRegistrar->save();

        return response()->json($newRegistrar, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/registrar",
     *     summary="List all registered users",
     *     description="List all users that have an account with the site",
     *     tags={"registrars"},
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Token of the user to delete",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Registrar deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad registrar token format",
     *         @OA\JsonContent(ref="#/components/schemas/BadRequestError")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Registrar token does not match any existing registrars",
     *         @OA\JsonContent(ref="#/components/schemas/NotFoundError")
     *     ),
     * )
     */
    public function deleteRegistrar(Request $request): \Illuminate\Http\JsonResponse
    {
        $deleteToken = $request->input('token', null);
        if ($deleteToken != null) {
            return response()->json(['error' => 'No token was specified'], 400);
        }
        $registrar = DB::table('registrars')
            ->where('token', '=', $deleteToken)
            ->first();
        if ($registrar == null) {
            return response()->json(['error' => 'No user with this token could be found'], 404);
        }

        DB::table('registrars')
            ->where('token', '=', $deleteToken)
            ->delete();
        
        return response()->json([], 204);
    }

    /**
     * @OA\Get(
     *     path="/api/registrar",
     *     summary="List all registrars",
     *     description="List all registrars that have an account with the site",
     *     tags={"registrars"},
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
     *                 @OA\Items(ref="#/components/schemas/Registrar")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized to list registrars",
     *         @OA\JsonContent(ref="#/components/schemas/UnauthorizedError")
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Forbidden to list the registrars. Only admins can perform this action",
     *         @OA\JsonContent(ref="#/components/schemas/UnauthorizedError")
     *     )
     * )
     *
     * @param Request $request Lumen request object
     * @return \Illuminate\Http\JsonResponse
     */
    public function listRegistrar(Request $request): \Illuminate\Http\JsonResponse
    {
        $per_page = $request->get('per_page', 15);
        return response()->json(Registrar::simplePaginate($per_page));
    }
}
