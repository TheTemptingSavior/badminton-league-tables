<?php

namespace App\Http\Controllers;

use App\Helpers\GenericHelper;
use App\Helpers\TeamHelper;
use App\Models\Season;
use App\Models\SeasonTeams;
use App\Models\Team;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            ->select(['id', 'name', 'slug'])
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
     *     summary="Retire team from seasons in the leage",
     *     description="Updates the retirement status of a team within the league. Note: Seasons can be omitted from the list and only present seasons will be updated",
     *     tags={"teams"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the team to edit the retire status of",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Seasons and whether the team is still active in it",
     *         @OA\JsonContent(
     *             propert="data",
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="active", type="boolean"),
     *                 @OA\Property(property="season", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns the newly edited team",
     *         @OA\JsonContent(ref="#/components/schemas/Team")
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad request data provided",
     *         @OA\JsonContent(ref="#/components/schemas/BadRequestError")
     *     ),
     *     @OA\Response(
     *         response="409",
     *         description="Team cannot be retired as they have scorecards in one of the leagues to be retired from",
     *         @OA\JsonContent(ref="#/components/schemas/ConflictError")
     *     )
     * )
     * @param string $id ID of the team to retire
     * @param Request $request Lumen request object
     * @return \Illuminate\Http\JsonResponse
     */
    public function retireTeam(string $id, Request $request): \Illuminate\Http\JsonResponse
    {
        // Validate the parameters
        $team = Team::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'data' => 'required|array',
            'data.*.season' => 'required|integer',
            'data.*.active' => 'required|boolean',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Get the data in a usable format
        $data = $request->toArray();

        // Start a transaction. Either all the season changes are applied
        // or none of them are
        DB::beginTransaction();
        foreach($data['data'] as $sa) {
            // Gather data
            $season = Season::find($sa['season'], "*");
            if ($season == null) {
                $sid = $sa['season'];
                Log::error("Season #{$sid} does not exist");
                DB::rollBack();
                return response()->json(
                    ['error' => "Season #{$sid} not found"],
                    400
                );
            }
            // Attempt to get the data from the season_teams table
            $row = DB::table('season_teams')
                ->where('season_id', '=', $season->id)
                ->where('team_id', '=', $team->id)
                ->first();

            if ($row == null && $sa['active'] == true) {
                Log::info("No SeasonTeams(season={$season->id}, team={$team->id}) row. Creating now");
                // There is no row but the team is active so create one
                $st = new SeasonTeams;
                $st->season_id = $season->id;
                $st->team_id = $team->id;
                $st->save();

                // Because a new entry is made the scoreboards must be updated
                $this->dispatch(new UpdateScoreboard($season->id));
            } else if ($row != null && $sa['active'] == false) {
                Log::info("SeasonTeams(season={$season->id}, team={$team->id}) exists. Deleting now");
                // There is a row but the team is not active
                if (TeamHelper::canRetire($id, $season->id)) {
                    Log::info("Team #{$team->id} is being retired");
                    DB::table('season_teams')->delete($row->id);

                    // Because an entry was deleted the scoreboards must be updated
                    $this->dispatch(new UpdateScoreboard($season->id));
                } else {
                    Log::warning("Team #{$team->id} still has scorecards. Aborting all operations");
                    DB::rollBack();
                    return response()->json(
                        ['error' => 'Team has active scorecards in season '.$season->slug],
                        400
                    );
                }
            } else if ($row == null && $sa['active'] == false) {
                Log::debug("No entry in SeasonTeams and active is false. Nothing to do");
            } else if ($row != null && $sa['active'] == true) {
                Log::debug("SeasonTeams already exists and active is true. Nothing to do");
            } else {
                Log::critical("Unknown combination of row and active! Aborting operation");
                DB::rollBack();
                return response()->json(
                    ['error' => 'An unknown error occurred'],
                    400
                );
            }
        }

        try {
            DB::commit();
        } catch (\Exception $e) {
            Log::error("Failed updating season retirement status");
            return response()->json(['error' => 'Failed updating team status', 'msg' => $e], 400);
        }

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

    /**
     * @OA\Get(
     *     path="/api/teams/{id}/seasons",
     *     summary="Get the teams activity in previous seasons",
     *     description="Gets the past list of seasons and whether or not the team was active or inactive in it",
     *     tags={"teams"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the team to get",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Seasons and whether the team was active in it",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="active",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Season")
     *             ),
     *             @OA\Property(
     *                 property="notactive",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Season")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad request data",
     *         @OA\JsonContent(ref="#/components/schemas/BadRequestError")
     *     )
     * )
     */
    public function getTeamSeasons(string $id): \Illuminate\Http\JsonResponse
    {
        $team = Team::findOrFail($id);

        $activeSeasons = DB::table('season_teams')
            ->where('team_id', '=', $team->id)
            ->get()
            ->toArray();
        $allSeasons = DB::table('seasons')
            ->get()
            ->toArray();

        $data = Array(
            'active' => Array(),
            'notactive' => Array(),
        );

        foreach ($allSeasons as $season) {
            $added = false;
            foreach($activeSeasons as $active) {
                if ($active->season_id === $season->id) {
                    array_push($data['active'], $season);
                    $added = true;
                    break;
                }
            }
            if (! $added) {
                array_push($data['notactive'], $season);
            }
        }

        return response()->json($data, 200);
    }
}
