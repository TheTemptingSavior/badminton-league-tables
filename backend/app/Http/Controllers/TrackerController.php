<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class TrackerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tracker",
     *     summary="Get games played by all teams for the current season",
     *     description="Get the games played by a team and the games they have yet to play",
     *     tags={"tracker"},
     *     @OA\Response(
     *         response="200",
     *         description="Played games",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", format="int64"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(
     *                     property="played",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", format="int64"),
     *                         @OA\Property(property="name", type="string")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="not_played",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", format="int64"),
     *                         @OA\Property(property="name", type="string")
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getCurrent()
    {
        $data = [];
        $season = DB::table('seasons')
            ->orderBy('start', 'desc')
            ->first();
        $teams = DB::table('season_teams')
            ->where('season_id', '=', $season->id)
            ->join('teams', 'season_teams.team_id', 'teams.id')
            ->select('teams.id', 'teams.name')
            ->get()
            ->toArray();
        $teamIds = array_column($teams, 'id');

        foreach($teams as $team) {
            $team->played = DB::table('scorecards')
                ->whereBetween('date_played', [$season->start, $season->end])
                ->where('home_team', '=', $team->id)
                ->join('teams', 'scorecards.away_team', 'teams.id')
                ->select(['teams.id', 'teams.name'])
                ->get()
                ->toArray();
            $team->not_played = [];;

            array_push($data, $team);
        }

        return response()->json($data, 200);
    }
}
