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
        ];
        $data['data'] = [];

        // Get a complete list of teams in this season
        $teams = DB::table('season_teams')
            ->where('season_id', '=', $season->id)
            ->join('teams', 'season_teams.team_id', 'teams.id')
            ->select('teams.id', 'teams.name')
            ->get()
            ->toArray();
        foreach($teams as $team) {
            $data['data'][$team->id] = [
                'id' => $team->id,
                'name' => $team->name,
                'played' => [],
                'not_played' => [],
            ];
        }
        $allTeamIds = array_keys($data['data']);

        foreach($teams as $team) {
            // List of played gamed
            $data['data'][$team->id]['played'] = DB::table('scorecards')
                ->whereBetween('date_played', [$season->start, $season->end])
                ->where('home_team', '=', $team->id)
                ->join('teams', 'scorecards.away_team', 'teams.id')
                ->select(['teams.id', 'teams.name', 'scorecards.id as scorecard_id'])
                ->get()
                ->toArray();

            $playedTeamIds = array_column($data['data'][$team->id]['played'], 'id');
            $notPlayedGames = array_filter(array_diff($allTeamIds, $playedTeamIds), function($id) use ($team) {
                return $team->id !== $id;
            });

            // TODO: Remove the constant database calls here
            $data['data'][$team->id]['not_played'] = [];
            foreach ($notPlayedGames as $notPlayedGame) {
                array_push(
                    $data['data'][$team->id]['not_played'],
                    [
                        'id' => $notPlayedGame,
                        'name' => $this->getTeamNameById($notPlayedGame, $teams)
                    ]
                );
            }
        }

        return response()->json($data, 200);
    }

    private function getTeamNameById(int $id, Array $teams)
    {
        foreach ($teams as $team) {
            if ($team->id === $id) {
                return $team->name;
            }
        }
        return -1;
    }
}
