<?php


namespace App\Http\Controllers;


use App\Models\Scorecard;
use Illuminate\Http\Request;

class ScorecardController extends Controller
{
    /**
     * Get a scorecard based upon its ID
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGame(string $id)
    {
        $game = Scorecard::findOrFail($id);

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
        $this->validate($request, Scorecard::getValidationRules());

        // Before running any validations against the data
        // ensure all the required keys are present
        $data = Scorecard::PAD_SCORECARD($request->toArray());
        $errors = Scorecard::validateData($data);
        if ($errors !== true) { return response()->json(["errors" => $errors], 400); }

        $scorecard = Scorecard::create($data);
        $warnings = Scorecard::checkData($data);


        if (sizeof($warnings) == 0) {
            return response()->json(
                ['message' => 'Scorecard created', 'id' => $scorecard->id, 'warnings' => null],
                201
            );
        } else {
            return response()->json(
                ['message' => 'Scorecard created', 'id' => $scorecard->id, 'warnings' => $warnings],
                201
            );
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
        $game = Scorecard::findOrFail($id);

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
        Scorecard::findOrFail($id)->delete();

        return response()->json([], 204);
    }
}
