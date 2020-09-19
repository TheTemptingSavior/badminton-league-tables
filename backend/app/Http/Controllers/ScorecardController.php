<?php


namespace App\Http\Controllers;

use App\Models\Scorecard;
use Illuminate\Http\Request;

class ScorecardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/scorecards/{id}",
     *     description="Get a scorecard based upon its ID",
     *     tags={"scorecards"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the scorecard to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Scorecard with the given ID",
     *         @OA\JsonContent(ref="#/components/schemas/Scorecard")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Scorecard with the given ID does not exist",
     *         @OA\JsonContent(ref="#/components/schemas/Scorecard")
     *     )
     * )
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGame(string $id)
    {
        $game = Scorecard::findOrFail($id);

        return response()->json($game, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/scorecards",
     *     description="Create a new scorecard in the system",
     *     tags={"scorecards"},
     *     security={"jwt_auth": ""},
     *     @OA\Response(
     *         response="201",
     *         description="Scorecard created",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", format="int64"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="warnings", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad request data",
     *         @OA\JsonContent(ref="#/components/schemas/BadRequestError")
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Authorized access only",
     *         @OA\JsonContent(ref="#/components/schemas/UnauthorizedError")
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Only admins can perform this action",
     *         @OA\JsonContent(ref="#/components/schemas/ForbiddenError")
     *     ),
     *     @OA\Response(
     *         response="409",
     *         description="Scorecard with this data already exists",
     *         @OA\JsonContent(ref="#/components/schemas/ConflictError")
     *     )
     * )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createGame(Request $request)
    {
        // TODO: Describe the responses
        $this->validate($request, Scorecard::getValidationRules());

        // Before running any validations against the data
        // ensure all the required keys are present
        $data = Scorecard::PAD_SCORECARD($request->toArray());
        $errors = Scorecard::validateData($data);
        if ($errors !== true) { return response()->json(["errors" => $errors], 400); }

        $scorecard = Scorecard::create($data);
        $warnings = Scorecard::checkData($data);


        if (sizeof($warnings) == 0) {
            return response()->json(
                ['message' => 'Scorecard created', 'id' => $scorecard->id, 'warnings' => null],
                201
            );
        } else {
            return response()->json(
                ['message' => 'Scorecard created', 'id' => $scorecard->id, 'warnings' => $warnings],
                201
            );
        }
    }

    /**
     * @OA\Put(
     *     path="/api/scorecards/{id}",
     *     description="Update a game based upon its ID",
     *     tags={"scorecards"},
     *     security={"jwt_auth": ""},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the scorecard to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\RequestBody(
     *         description="Scorecard data to update",
     *         @OA\JsonContent(ref="#/components/schemas/Scorecard")
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Scorecard updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", format="int64"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="warnings", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Not authorized to perform this action",
     *         @OA\JsonContent(ref="#/components/schemas/UnauthorizedError")
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Only admins may perform this action",
     *         @OA\JsonContent(ref="#/components/schemas/ForbiddenError")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Scorecard with the specified ID not found",
     *         @OA\JsonContent(ref="#/components/schemas/NotFoundError")
     *     ),
     *     @OA\Response(
     *         response="409",
     *         description="Conflict in the scorecard data",
     *         @OA\JsonContent(ref="#/components/schemas/ConflictError")
     *     )
     * )
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateGame(string $id)
    {
        // TODO: Implement this
        $game = Scorecard::findOrFail($id);

        // Update the game
        return response()->json($game, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/scorecards/{id}",
     *     description="Deletes a scorecard from the system",
     *     tags={"scorecards"},
     *     security={"jwt_auth": ""},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the scorecard to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Scorecard deleted"
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Not authorized to perform this action",
     *         @OA\JsonContent(ref="#/components/schemas/UnauthorizedError")
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Only admins may perform this action",
     *         @OA\JsonContent(ref="#/components/schemas/ForbiddenError")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Scorecard with the specified ID not found",
     *         @OA\JsonContent(ref="#/components/schemas/NotFoundError")
     *     )
     * )
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteGame(string $id)
    {
        Scorecard::findOrFail($id)->delete();

        return response()->json([], 204);
    }
}
