<?php

namespace App\Http\Controllers;

use App\Helpers\GenericHelper;
use App\Helpers\SeasonHelper;
use App\Models\Season;
use Illuminate\Support\Facades\DB;


class SeasonController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/seasons",
     *     summary="List seasons",
     *     description="List all seasons that have been played",
     *     tags={"seasons"},
     *     @OA\Response(
     *         response="200",
     *         description="List of seasons the league has been active for",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Season")
     *         )
     *     )
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function listSeasons(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Season::all());
    }

    /**
     * @OA\Get(
     *     path="/api/seasons/current",
     *     summary="Get the current season",
     *     description="Returns the current season and teams playing",
     *     tags={"seasons"},
     *     @OA\Response(
     *         response="200",
     *         description="Season data",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="slug", type="string"),
     *             @OA\Property(property="start", type="string"),
     *             @OA\Property(property="end", type="string"),
     *             @OA\Property(property="created_at", type="string"),
     *             @OA\Property(property="updated_at", type="string"),
     *             @OA\Property(
     *                 type="teams",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Team")
     *             )
     *         )
     *     )
     * )
     */
    public function currentSeason(): \Illuminate\Http\JsonResponse
    {
        $season = SeasonHelper::getCurrent();
        $teams = DB::table('season_teams')
            ->where('season_id', '=', $season->id)
            ->join('teams', 'season_teams.team_id', 'teams.id')
            ->select('teams.id', 'teams.name', 'teams.slug', 'teams.retired_on', 'teams.created_at', 'teams.updated_at')
            ->get();

        $data = Array(
            "id" => $season->id,
            "slug" => $season->slug,
            "start" => $season->start,
            "end" => $season->end,
            "created_at" => $season->created_at,
            "updated_at" => $season->updated_at,
            "teams" => $teams
        );

        return response()->json($data, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/seasons/{id}",
     *     summary="Get a single season",
     *     description="Returns information about the season identified by its ID",
     *     tags={"seasons"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the team to edit the retire status of",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Season data",
     *         @OA\JsonContent(ref="#/components/schemas/Season")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="No season found with the given ID",
     *         @OA\JsonContent(ref="#/components/schemas/NotFoundError")
     *     )
     * )
     * @param string $id ID of the season to retrieve
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSeason(string $id): \Illuminate\Http\JsonResponse
    {
        $season = Season::findOrFail($id);
        return response()->json($season, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/seasons/{id}/teams",
     *     summary="List teams in a season",
     *     description="Returns a list of the teams playing in the season identified by the given ID",
     *     tags={"seasons"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the team to edit the retire status of",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="List of teams in the given season",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Team")
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Season with the given ID not found",
     *         @OA\JsonContent(ref="#/components/schemas/NotFoundError")
     *     )
     * )
     * @param string $id ID of season to retrieve the teams from
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTeams(string $id): \Illuminate\Http\JsonResponse
    {
        $teams = DB::table('season_teams')
            ->where('season_id', '=', $id)
            ->join('teams', 'season_teams.team_id', 'teams.id')
            ->select('teams.id', 'teams.name', 'teams.slug', 'teams.retired_on', 'teams.created_at', 'teams.updated_at')
            ->get();

        return response()->json($teams, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/seasons/fromslug/{slug}",
     *     summary="Get season from slug",
     *     description="Returns a season with the given slug",
     *     tags={"seasons"},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         description="Slug of the season to find",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Season with the slug specified",
     *         @OA\JsonContent(ref="#/components/schemas/Season")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Season not found with the given slug",
     *         @OA\JsonContent(ref="#/components/schemas/NotFoundError")
     *     )
     * )
     * @param string $slug Slug of the season to return
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFromSlug(string $slug): \Illuminate\Http\JsonResponse
    {
        // TODO: Create catch for graceful 404 return if this fails
        $season = DB::table('seasons')
            ->where('slug', '=', $slug)
            ->first();

        return response()->json($season, 200);
    }


    /**
     * @OA\Get(
     *     path="/api/seasons/{id}/scorecards",
     *     summary="List scorecards in a season",
     *     description="List all the scorecards that were registered in a season",
     *     tags={"seasons"},
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
     *         description="List of scorecards played in the season",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="home_team", type="integer"),
     *                 @OA\Property(property="away_team", type="integer"),
     *                 @OA\Property(property="date_played", type="string", format="datetime"),
     *                 @OA\Property(property="home_points", type="integer"),
     *                 @OA\Property(property="away_points", type="integer")
     *             )
     *         )
     *     )
     * )
     * @param string $slug Slug of the season to retrieve
     * @return \Illuminate\Http\JsonResponse
     */
    public function getScorecards(string $id): \Illuminate\Http\JsonResponse
    {
        // TODO: Create a catch if this fails to return 404
        $season = DB::table('seasons')
            ->where('id', '=', $id)
            ->first();

        $games = DB::table('scorecards')
            ->whereBetween('date_played', [$season->start, $season->end])
            ->get();

        return response()->json($games, 200);
    }
}
