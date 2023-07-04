<?php

namespace App\Http\Controllers;

use App\Models\Registrar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
            if (array_key_exists('email', $errors) and strpos(implode(" ", $errors['email']), 'taken')) {
                $code = 409;
            } else {
                $code = 400;
            }
            return response()->json($validator->errors(), $code);
        }
        $newRegistrar = new Registrar;
        $newRegistrar->email = $request->email;
        $newRegistrar->save();

        return response()->json($newRegistrar, 201);
    }
}
