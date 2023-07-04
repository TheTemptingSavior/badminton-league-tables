<?php

namespace App\Http\Controllers;

use App\Helpers\GenericHelpers;
use App\Models\Team;
use Illuminate\Http\Request;


class TeamController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/teams",
     *     description="List all teams that exist",
     *     tags={"teams"},
     *     @OA\Response(
     *         response="200",
     *         description="List of teams in the league",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Team")
     *         )
     *     )
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function listTeams()
    {
        return response()->json(Team::all());
    }

    /**
     * @OA\Get(
     *     path="/api/teams/{id}",
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
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTeam(string $id)
    {
        $team = Team::findOrFail($id);
        return response()->json($team, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/teams",
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createTeam(Request $request)
    {
        $this->validate($request, ['name' => 'required|unique:teams']);
        $name = $request->name;
        $slug = GenericHelpers::slugify($name);

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
     * @param string $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function retireTeam(string $id, Request $request)
    {
        $this->validate($request, ['retired' => 'required|boolean']);

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
     * @param string $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateTeam(string $id, Request $request)
    {
        $this->validate($request, ['name' => 'unique:teams']);

        $team = Team::findOrFail($id);

        if ($request->name) {
            $team->name = $request->name;
            $team->slug = str_slug($request->name, "-");
            $team->save();

            return response()->json($team, 200);
        }

        return response()->json(['error' => 'Team name must be present in request and unique'], 400);
    }
}
