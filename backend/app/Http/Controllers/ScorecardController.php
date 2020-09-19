<?php


namespace App\Http\Controllers;

use App\Models\Scorecard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScorecardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/scorecards",
     *     summary="Get all scorecards",
     *     description="Get a list of all the scorecards in the system. By default this is ordered by date played",
     *     tags={"scorecards"},
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
     *         description="Reduced scorecard data",
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
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", format="int64"),
     *                     @OA\Property(property="home_team", type="string"),
     *                     @OA\Property(property="away_team", type="string"),
     *                     @OA\Property(property="date_played", type="string", format="date"),
     *                     @OA\Property(property="home_points", type="integer", format="int64"),
     *                     @OA\Property(property="away_points", type="integer", format="int64")
     *                 )
     *             )
     *         )
     *     )
     * )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(Request $request)
    {
        // Page parameter isn't needed as it happens automatically
        $per_page = $request->get('per_page', '15');
        $data = DB::table('scorecards')
            ->orderBy('date_played')
            ->select(['id', 'home_team', 'away_team', 'date_played', 'home_points', 'away_points'])
            ->simplePaginate($per_page);

        return response()->json($data, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/scorecards/{id}",
     *     summary="Get a single scorecard",
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
     *     summary="Create scorecard",
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
     *     summary="Update scorecard",
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
     *     summary="Delete a scorecard",
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