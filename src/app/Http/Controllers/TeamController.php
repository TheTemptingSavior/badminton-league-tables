<?php

namespace App\Http\Controllers;

use App\Helpers\GenericHelpers;
use App\Models\Team;
use Illuminate\Http\Request;


class TeamController extends Controller
{
    /**
     * List all teams that exist
     * @return \Illuminate\Http\JsonResponse
     */
    public function listTeams()
    {
        return response()->json(Team::all());
    }

    /**
     * Returns information about the team identified by its ID
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTeam(string $id)
    {
        $team = Team::findOrFail($id);
        return response()->json($team, 200);
    }

    /**
     * Create a new team for the league
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createTeam(Request $request)
    {
        request()->validate(['name' => 'required|unique:teams']);
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
     * Retires a team in the league, removing it from options in games
     * and removing them from future league tables
     * @param string $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function retireTeam(string $id, Request $request)
    {
        request()->validate(['retired' => 'required|boolean']);

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
     * Update a teams name based on their ID
     * @param string $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateTeam(string $id, Request $request)
    {
        request()->validate(['name' => 'unique:teams']);

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
