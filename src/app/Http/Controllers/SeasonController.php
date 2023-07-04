<?php

namespace App\Http\Controllers;

use App\Models\Season;
use App\Models\SeasonTeams;
use Illuminate\Support\Facades\DB;


class SeasonController extends Controller
{
    /**
     * List all seasons that have been played
     * @return \Illuminate\Http\JsonResponse
     */
    public function listSeasons()
    {
        return response()->json(Season::all());
    }

    /**
     * Returns information about the season identified by its ID
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSeason(string $id)
    {
        $season = Season::findOrFail($id);
        return response()->json($season, 200);
    }

    /**
     * Returns a list of the teams playing in the season indentified
     * by the given ID
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTeams(string $id)
    {
        $teams = DB::table('season_teams')
            ->where('season_id', '=', $id)
            ->join('teams', 'season_teams.team_id', 'teams.id')
            ->select('teams.id', 'teams.name', 'teams.slug')
            ->get();

        return response()->json($teams, 200);
    }
}
