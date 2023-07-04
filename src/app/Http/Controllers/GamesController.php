<?php


namespace App\Http\Controllers;


use App\Models\Game;
use Illuminate\Http\Request;

class GamesController extends Controller
{
    public function createGame(Request $request)
    {
        $this->validate(
            $request,
            Game::getValidationRules()
        );

        
    }
}
