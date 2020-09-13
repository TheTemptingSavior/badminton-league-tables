<?php


namespace App\Http\Controllers;


use App\Models\Game;
use Illuminate\Http\Request;

class GamesController extends Controller
{
    /**
     * Get a scorecard based upon its ID
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGame(string $id)
    {
        $game = Game::findOrFail($id);

        return response()->json($game, 200);
    }

    /**
     * Create a new scorecard in the system
     * TODO: Describe the responses
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createGame(Request $request)
    {
        $this->validate(
            $request,
            Game::getValidationRules()
        );
        $errors = Game::validateData($request->toArray());
        if ($errors !== false) { return response()->json(["errors" => $errors], 400); }

        $warnings = Game::checkData($request->toArray());
        if (sizeof($warnings) == 0) {
            return response()->json(["message" => "Game created"], 201);
        } else {
            return response()->json(["message" => "Game created", "warnings" => $warnings], 201);
        }
    }

    /**
     * Update a game based upon its ID
     * TODO: Implement this
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateGame(string $id)
    {
        $game = Game::findOrFail($id);

        // Update the game
        return response()->json($game, 200);
    }

    /**
     * Deletes a scorecard from the system
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteGame(string $id)
    {
        Game::findOrFail($id)->delete();

        return response()->json([], 204);
    }
}
