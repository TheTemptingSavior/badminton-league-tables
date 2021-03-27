<?php

namespace App\Http\Controllers;

use App\Models\Season;
use Illuminate\Support\Facades\DB;

class TrackerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tracker/current",
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
    public function getCurrent(): \Illuminate\Http\JsonResponse
    {
        $season = DB::table('seasons')
            ->orderBy('start', 'desc')
            ->select(['id', 'slug', 'start', 'end'])
            ->first();
        $data = $this->getData($season);

        return response()->json($data, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/tracker/{id}",
     *     summary="Get games played by all teams for the specified season",
     *     description="Get the games played by a team and the games they have yet to play for a specific season",
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
     * @param string $id ID of the season to find a tracker for
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTracker(string $id): \Illuminate\Http\JsonResponse
    {
        $season = Season::findOrFail($id);
        $data = $this->getData($season);

        return response()->json($data, 200);
    }

    /**
     * Gets the tracker data for the given season
     * @param $season Season Season to get the data for
     * @return mixed Array of season data (array of arrays)
     */
    private function getData($season): mixed
    {
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
            // List of played games
            $data['data'][$team->id]['played'] = DB::table('scorecards')
                ->whereBetween('date_played', [$season->start, $season->end])
                ->where('home_team', '=', $team->id)
                ->join('teams', 'scorecards.away_team', 'teams.id')
                ->select(['teams.id', 'teams.name', 'scorecards.id as scorecard_id'])
                ->get()
                ->toArray();

            $playedTeamIds = array_column($data['data'][$team->id]['played'], 'id');
            $notPlayedGames = array_filter(
                array_diff($allTeamIds, $playedTeamIds),
                function($id) use ($team) {
                    return $team->id !== $id;
                }
            );

            // TODO: Remove the constant database calls here for efficiency (cache it earlier on)
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

        return $data;
    }

    /**
     * Searches the teams array for a team with the given ID and returns the name
     * @param int $id ID of the team to find
     * @param array $teams List of all the teams
     * @return string Team name
     */
    private function getTeamNameById(int $id, Array $teams): string
    {
        foreach ($teams as $team) {
            if ($team->id === $id) {
                return $team->name;
            }
        }
        return "";
    }
}
