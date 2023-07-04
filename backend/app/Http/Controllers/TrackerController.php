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
        // Get the current season
        $season = DB::table('seasons')
            ->orderBy('start', 'desc')
            ->first();

        // Set up our return object
        $data['season'] = [
            'id' => $season->id,
            'start' => $season->start,
            'end' => $season->end,
            'slug' => $season->slug,
            'data' => []
        ];

        // Get a complete list of teams in this season
        $teams = DB::table('season_teams')
            ->where('season_id', '=', $season->id)
            ->join('teams', 'season_teams.team_id', 'teams.id')
            ->select('teams.id', 'teams.name')
            ->get()
            ->toArray();
        $allTeamIds = array_column($teams, 'id');

        foreach($teams as $team) {
            // List of played gamed
            $team->played = DB::table('scorecards')
                ->whereBetween('date_played', [$season->start, $season->end])
                ->where('home_team', '=', $team->id)
                ->join('teams', 'scorecards.away_team', 'teams.id')
                ->select(['teams.id', 'teams.name'])
                ->get()
                ->toArray();
            $playedTeamIds = array_column($team->played, 'id');
            $notPlayedGames = array_filter(array_diff($allTeamIds, $playedTeamIds), function($id) use ($team) {
                return $team->id !== $id;
            });

            // TODO: Remove the constant database calls here
            $team->not_played = array_map(function ($id) use ($teams) {
                // Search in the $teams variable as that is in memory
                return [
                    'id' => $id,
                    'name' => DB::table('teams')->where('id', '=', $id)->first()->name
                ];
            }, $notPlayedGames);

            $data['data'][$team->id] = $team;
        }

        return response()->json($data, 200);
    }
}
