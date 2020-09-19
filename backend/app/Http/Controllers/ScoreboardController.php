<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class ScoreboardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/scoreboards/{slug}",
     *     summary="Get a scoreboard",
     *     description="Get a scoreboard for a specific season based on the season slug",
     *     tags={"scoreboards"},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         description="Slug of the season to retrieve",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Scoreboard data for the given season",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Scoreboard")
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Could not find a season for the given slug",
     *         @OA\JsonContent(ref="#/components/schemas/NotFoundError")
     *     )
     * )
     * @param string|null $season
     * @return \Illuminate\Http\JsonResponse
     */
    public function getScoreboard(string $season = null)
    {
        return response()->json([], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/scoreboards/all",
     *     summary="Get all scoreboard",
     *     description="List the available scoreboards (by season)",
     *     tags={"scoreboards"},
     *     @OA\Response(
     *         response="200",
     *         description="List of available seasons",
     *         @OA\JsonContent(type="array", @OA\Items(type="string"))
     *     )
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return response()->json([], 200);
    }
}
