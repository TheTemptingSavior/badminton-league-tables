<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScorecardsTable extends Migration
{
    public function up()
    {
        Schema::create('scorecards', function (Blueprint $table) {
            $table->increments('id');
            // Match metadata
            $table->integer('home_team')->unsigned();
            $table->integer('away_team')->unsigned();
            $table->foreign('home_team')->references('id')->on('teams');
            $table->foreign('away_team')->references('id')->on('teams');
            $table->date('date_played');
            $table->integer('home_points');
            $table->integer('away_points');

            // Match players
            $table->string('home_player_1')->nullable();
            $table->string('home_player_2')->nullable();
            $table->string('home_player_3')->nullable();
            $table->string('home_player_4')->nullable();
            $table->string('home_player_5')->nullable();
            $table->string('home_player_6')->nullable();
            $table->string('away_player_1')->nullable();
            $table->string('away_player_2')->nullable();
            $table->string('away_player_3')->nullable();
            $table->string('away_player_4')->nullable();
            $table->string('away_player_5')->nullable();
            $table->string('away_player_6')->nullable();

            // Home 1 vs Away 1
            $table->integer('game_one_v_one_home_one')->nullable();
            $table->integer('game_one_v_one_away_one')->nullable();
            $table->integer('game_one_v_one_home_two')->nullable();
            $table->integer('game_one_v_one_away_two')->nullable();
            $table->integer('game_one_v_one_home_three')->nullable();
            $table->integer('game_one_v_one_away_three')->nullable();

            // Home 1 vs Away 2
            $table->integer('game_one_v_two_home_one')->nullable();
            $table->integer('game_one_v_two_away_one')->nullable();
            $table->integer('game_one_v_two_home_two')->nullable();
            $table->integer('game_one_v_two_away_two')->nullable();
            $table->integer('game_one_v_two_home_three')->nullable();
            $table->integer('game_one_v_two_away_three')->nullable();

            // Home 1 vs Away 3
            $table->integer('game_one_v_three_home_one')->nullable();
            $table->integer('game_one_v_three_away_one')->nullable();
            $table->integer('game_one_v_three_home_two')->nullable();
            $table->integer('game_one_v_three_away_two')->nullable();
            $table->integer('game_one_v_three_home_three')->nullable();
            $table->integer('game_one_v_three_away_three')->nullable();

            // Home 2 vs Away 1
            $table->integer('game_two_v_one_home_one')->nullable();
            $table->integer('game_two_v_one_away_one')->nullable();
            $table->integer('game_two_v_one_home_two')->nullable();
            $table->integer('game_two_v_one_away_two')->nullable();
            $table->integer('game_two_v_one_home_three')->nullable();
            $table->integer('game_two_v_one_away_three')->nullable();

            // Home 2 vs Away 2
            $table->integer('game_two_v_two_home_one')->nullable();
            $table->integer('game_two_v_two_away_one')->nullable();
            $table->integer('game_two_v_two_home_two')->nullable();
            $table->integer('game_two_v_two_away_two')->nullable();
            $table->integer('game_two_v_two_home_three')->nullable();
            $table->integer('game_two_v_two_away_three')->nullable();

            // Home 2 vs Away 3
            $table->integer('game_two_v_three_home_one')->nullable();
            $table->integer('game_two_v_three_away_one')->nullable();
            $table->integer('game_two_v_three_home_two')->nullable();
            $table->integer('game_two_v_three_away_two')->nullable();
            $table->integer('game_two_v_three_home_three')->nullable();
            $table->integer('game_two_v_three_away_three')->nullable();

            // Home 3 vs Away 1
            $table->integer('game_three_v_one_home_one')->nullable();
            $table->integer('game_three_v_one_away_one')->nullable();
            $table->integer('game_three_v_one_home_two')->nullable();
            $table->integer('game_three_v_one_away_two')->nullable();
            $table->integer('game_three_v_one_home_three')->nullable();
            $table->integer('game_three_v_one_away_three')->nullable();

            // Home 3 vs Away 2
            $table->integer('game_three_v_two_home_one')->nullable();
            $table->integer('game_three_v_two_away_one')->nullable();
            $table->integer('game_three_v_two_home_two')->nullable();
            $table->integer('game_three_v_two_away_two')->nullable();
            $table->integer('game_three_v_two_home_three')->nullable();
            $table->integer('game_three_v_two_away_three')->nullable();

            // Home 3 vs Away 3
            $table->integer('game_three_v_three_home_one')->nullable();
            $table->integer('game_three_v_three_away_one')->nullable();
            $table->integer('game_three_v_three_home_two')->nullable();
            $table->integer('game_three_v_three_away_two')->nullable();
            $table->integer('game_three_v_three_home_three')->nullable();
            $table->integer('game_three_v_three_away_three')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('scorecards');
    }
}
