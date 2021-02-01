<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ScoreboardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/scoreboards",
     *     summary="Get current scoreboard",
     *     description="Get the current scoreboard",
     *     tags={"scoreboards"},
     *     @OA\Response(
     *         response="200",
     *         description="Scoreboard data for the current season",
     *         @OA\JsonContent(ref="#/components/schemas/Scoreboard")
     *     )
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrent(): \Illuminate\Http\JsonResponse
    {
        // Get the seasons ordered by date and select the first one
        // This will be the latest season in the league
        $season = DB::table('seasons')
            ->orderBy('start', 'desc')
            ->first();

        $data = DB::table('scoreboards')
            ->where('season', '=', $season->id)
            ->orderBy('points', 'DESC')
            ->orderBy('wins', 'DESC')
            ->get();

        return response()->json(
            [
                'season' => $season->id,
                'slug' => $season->slug,
                'data' => $data,
            ],
            200
        );

    }

    /**
     * @OA\Get(
     *     path="/api/scoreboards/{id}",
     *     summary="Get a scoreboard",
     *     description="Get a scoreboard for a specific season based on the season ID",
     *     tags={"scoreboards"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the season to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Scoreboard data for the given season",
     *         @OA\JsonContent(ref="#/components/schemas/Scoreboard")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Could not find a season for the given slug",
     *         @OA\JsonContent(ref="#/components/schemas/NotFoundError")
     *     )
     * )
     * @param string $id ID of the season to retrieve a scoreboard for
     * @return \Illuminate\Http\JsonResponse
     */
    public function getScoreboard(string $id): \Illuminate\Http\JsonResponse
    {
        $season = DB::table('seasons')
            ->where('id', '=', $id)
            ->first();

        if ($season == null) {
            return response()->json(
                ['error' => 'Season not found'],
                404
            );
        }

        $data = DB::table('scoreboards')
            ->where('season', '=', $season->id)
            ->orderBy('points', 'DESC')
            ->get();

        return response()->json(
            [
                'season' => $season->id,
                'slug' => $season->slug,
                'data' => $data,
            ],
            200
        );
    }

    /**
     * @OA\Get(
     *     path="/api/scoreboards/all",
     *     summary="Get all scoreboard",
     *     description="List the available scoreboards (by season)",
     *     tags={"scoreboards"},
     *     @OA\Response(
     *         response="200",
     *         description="List of available scoreboards",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Scoreboard")
     *         )
     *     )
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(): \Illuminate\Http\JsonResponse
    {
        return response()->json([], 200);
    }
}
