<?php

namespace App\Http\Controllers;

use App\Helpers\GenericHelper;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class TeamController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/teams",
     *     summary="List teams",
     *     description="List all teams that exist",
     *     tags={"teams"},
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
     *         description="List of seasons the league has been active for",
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
     *                 @OA\Items(ref="#/components/schemas/Team")
     *             )
     *         )
     *     )
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function listTeams(Request $request): \Illuminate\Http\JsonResponse
    {
        $per_page = $request->get('per_page', 15);
        $data = DB::table('teams')
            ->orderBy('name')
            ->select(['id', 'name', 'slug', 'retired_on'])
            ->simplePaginate($per_page);
        return response()->json($data, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/teams/{id}",
     *     summary="Get team info",
     *     description="Returns information about the team identified by its ID",
     *     tags={"teams"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the team to edit the retire status of",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Team data",
     *         @OA\JsonContent(ref="#/components/schemas/Team")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not team with the given ID could be found",
     *         @OA\JsonContent(ref="#/components/schemas/NotFoundError")
     *     )
     * )
     * @param string $id ID of the team to retrieve
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTeam(string $id): \Illuminate\Http\JsonResponse
    {
        $team = Team::findOrFail($id);
        return response()->json($team, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/teams",
     *     summary="Create a team",
     *     description="Create a new team for the league",
     *     tags={"teams"},
     *     security={"jwt_auth": ""},
     *     @OA\RequestBody(
     *         required=true,
     *         description="New team information",
     *         @OA\Property(property="name", type="string")
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Team created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Team")
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad request data provided",
     *         @OA\JsonContent(ref="#/components/schemas/BadRequestError")
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized access to create a team",
     *         @OA\JsonContent(ref="#/components/schemas/UnauthorizedError")
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Only admins can create new teams",
     *         @OA\JsonContent(ref="#/components/schemas/ForbiddenError")
     *     ),
     *     @OA\Response(
     *         response="409",
     *         description="Team already exists with this name",
     *         @OA\JsonContent(ref="#/components/schemas/ConflictError")
     *     )
     * )
     * @param Request $request Lumen request object
     * @return \Illuminate\Http\JsonResponse
     */
    public function createTeam(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), ['name' => 'required|unique:teams']);
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            if (array_key_exists('name', $errors) and str_contains(implode(" ", $errors['name']), 'taken')) {
                // Means there is conflicting data in the database
                $code = 409;
            } else {
                $code = 400;
            }
            return response()->json($validator->errors(), $code);
        }
        $name = $request->name;
        $slug = GenericHelper::slugify($name);

        $newTeam = new Team;
        $newTeam->name = $name;
        $newTeam->slug = $slug;
        $newTeam->created_at = date('Y-m-d H:i:s');
        $newTeam->save();

        return response()->json($newTeam, 201);
    }

    /**
     * @OA\Post(
     *     path="/api/teams/{id}/retire",
     *     summary="Retire team from the league",
     *     description="Retires a team in the league, removing it from options in games and removing them from future league tables",
     *     tags={"teams"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Team retirement status",
     *         @OA\JsonContent(
     *             @OA\Property(property="retired", type="boolean")
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the team to edit the retire status of",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns the newly edited team",
     *         @OA\JsonContent(ref="#/components/schemas/Team")
     *     )
     * )
     * @param string $id ID of the team to retire
     * @param Request $request Lumen request object
     * @return \Illuminate\Http\JsonResponse
     */
    public function retireTeam(string $id, Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), ['retired' => 'required|boolean']);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $team = Team::findOrFail($id);
        if ($request->retired) {
            $team->retired_on = date('Y-m-d');
        } else {
            $team->retired_on = null;
        }
        $team->save();

        return response()->json($team, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/teams/{id}",
     *     summary="Update a team",
     *     description="Update a teams name based on their ID",
     *     tags={"teams"},
     *     security={"jwt_auth": ""},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the team to edit",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="New information for the team",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successfully edited team",
     *         @OA\JsonContent(ref="#/components/schemas/Team")
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad request data",
     *         @OA\JsonContent(ref="#/components/schemas/BadRequestError")
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized to perform this action",
     *         @OA\JsonContent(ref="#/components/schemas/UnauthorizedError")
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Only admins may edit team data",
     *         @OA\JsonContent(ref="#/components/schemas/ForbiddenError")
     *     ),
     *     @OA\Response(
     *         response="409",
     *         description="Conflict in team name",
     *         @OA\JsonContent(ref="#/components/schemas/ConflictError")
     *     )
     * )
     * @param string $id ID of the team to update
     * @param Request $request Lumen request object
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateTeam(string $id, Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, ['name' => 'unique:teams']);

        $team = Team::findOrFail($id);

        if ($request->name) {
            $team->name = $request->name;
            $team->slug = GenericHelper::slugify($request->name);
            $team->save();

            return response()->json($team, 200);
        }

        return response()->json(
            ['error' => 'Team name must be present in request and unique'],
            400
        );
    }
}
