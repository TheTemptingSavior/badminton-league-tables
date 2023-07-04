<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


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
        $this->validate(
            $request,
            [
                'name' => 'required|unique:teams',
            ]
        );
        $name = $request->name;
        // convert the name to lower and replace all spaces with a hyphen
        // TODO: Strip for special chars too
        $slug = strtolower(str_replace(" ", "-", $name));

        $newTeam = new Team;
        $newTeam->name = $name;
        $newTeam->slug = $slug;
        $newTeam->created_at = date('Y-m-d H:i:s');
        $newTeam->save();

        return response()->json($newTeam, 201);
    }
}
