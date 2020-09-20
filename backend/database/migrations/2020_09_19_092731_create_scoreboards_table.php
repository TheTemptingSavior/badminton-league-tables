<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScoreboardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scoreboards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('team')->unsigned();
            $table->foreign('team')->references('id')->on('teams');
            $table->integer('season')->unsigned();
            $table->foreign('season')->references('id')->on('seasons');
            $table->integer('played')->default(0);
            $table->integer('points')->default(0);
            $table->integer('wins')->default(0);
            $table->integer('losses')->default(0);
            $table->integer('for')->default(0);
            $table->integer('against')->default(0);
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
        Schema::dropIfExists('scoreboards');
    }
}
