<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            // Match metadata
            $table->foreignId('home_team')->constrained('teams');
            $table->foreignId('away_team')->constrained('teams');
            $table->date('date_played');
            $table->integer('home_score');
            $table->integer('away_score');

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
            $table->integer('game_one_v_one_game_home_one')->nullable();
            $table->integer('game_one_v_one_game_away_one')->nullable();
            $table->integer('game_one_v_one_game_home_two')->nullable();
            $table->integer('game_one_v_one_game_away_two')->nullable();
            $table->integer('game_one_v_one_game_home_three')->nullable();
            $table->integer('game_one_v_one_game_away_three')->nullable();

            // Home 1 vs Away 2
            $table->integer('game_one_v_two_game_home_one')->nullable();
            $table->integer('game_one_v_two_game_away_one')->nullable();
            $table->integer('game_one_v_two_game_home_two')->nullable();
            $table->integer('game_one_v_two_game_away_two')->nullable();
            $table->integer('game_one_v_two_game_home_three')->nullable();
            $table->integer('game_one_v_two_game_away_three')->nullable();

            // Home 1 vs Away 3
            $table->integer('game_one_v_three_game_home_one')->nullable();
            $table->integer('game_one_v_three_game_away_one')->nullable();
            $table->integer('game_one_v_three_game_home_two')->nullable();
            $table->integer('game_one_v_three_game_away_two')->nullable();
            $table->integer('game_one_v_three_game_home_three')->nullable();
            $table->integer('game_one_v_three_game_away_three')->nullable();

            // Home 2 vs Away 1
            $table->integer('game_two_v_one_game_home_one')->nullable();
            $table->integer('game_two_v_one_game_away_one')->nullable();
            $table->integer('game_two_v_one_game_home_two')->nullable();
            $table->integer('game_two_v_one_game_away_two')->nullable();
            $table->integer('game_two_v_one_game_home_three')->nullable();
            $table->integer('game_two_v_one_game_away_three')->nullable();

            // Home 2 vs Away 2
            $table->integer('game_two_v_two_game_home_one')->nullable();
            $table->integer('game_two_v_two_game_away_one')->nullable();
            $table->integer('game_two_v_two_game_home_two')->nullable();
            $table->integer('game_two_v_two_game_away_two')->nullable();
            $table->integer('game_two_v_two_game_home_three')->nullable();
            $table->integer('game_two_v_two_game_away_three')->nullable();

            // Home 2 vs Away 3
            $table->integer('game_two_v_three_game_home_one')->nullable();
            $table->integer('game_two_v_three_game_away_one')->nullable();
            $table->integer('game_two_v_three_game_home_two')->nullable();
            $table->integer('game_two_v_three_game_away_two')->nullable();
            $table->integer('game_two_v_three_game_home_three')->nullable();
            $table->integer('game_two_v_three_game_away_three')->nullable();

            // Home 3 vs Away 1
            $table->integer('game_three_v_one_game_home_one')->nullable();
            $table->integer('game_three_v_one_game_away_one')->nullable();
            $table->integer('game_three_v_one_game_home_two')->nullable();
            $table->integer('game_three_v_one_game_away_two')->nullable();
            $table->integer('game_three_v_one_game_home_three')->nullable();
            $table->integer('game_three_v_one_game_away_three')->nullable();

            // Home 3 vs Away 2
            $table->integer('game_three_v_two_game_home_one')->nullable();
            $table->integer('game_three_v_two_game_away_one')->nullable();
            $table->integer('game_three_v_two_game_home_two')->nullable();
            $table->integer('game_three_v_two_game_away_two')->nullable();
            $table->integer('game_three_v_two_game_home_three')->nullable();
            $table->integer('game_three_v_two_game_away_three')->nullable();

            // Home 3 vs Away 3
            $table->integer('game_three_v_three_game_home_one')->nullable();
            $table->integer('game_three_v_three_game_away_one')->nullable();
            $table->integer('game_three_v_three_game_home_two')->nullable();
            $table->integer('game_three_v_three_game_away_two')->nullable();
            $table->integer('game_three_v_three_game_home_three')->nullable();
            $table->integer('game_three_v_three_game_away_three')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
