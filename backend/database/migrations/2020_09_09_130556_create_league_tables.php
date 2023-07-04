<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeagueTables extends Migration
{
    public function up()
    {
        // Create a table for the teams in the league
        Schema::create('teams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Create a table to represent the seasons
        Schema::create('seasons', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Create a table to keep track of which teams play in each season
        Schema::create('season_teams', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('season_id')->unsigned();
            $table->integer('team_id')->unsigned();
            $table->foreign('season_id')->references('id')->on('seasons');
            $table->foreign('team_id')->references('id')->on('teams');
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('season_teams');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('seasons');
        Schema::enableForeignKeyConstraints();
    }
}
