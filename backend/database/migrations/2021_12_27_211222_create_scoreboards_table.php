<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScoreboardsTable extends Migration
{

    public function up()
    {
        Schema::create('scoreboards', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('team')->unsigned();
            $table->foreign('team')->references('id')->on('teams');
            $table->unsignedBigInteger('season')->unsigned();
            $table->foreign('season')->references('id')->on('seasons');
            $table->integer('played')->default(0);
            $table->integer('points')->default(0);
            $table->integer('wins')->default(0);
            $table->integer('losses')->default(0);
            $table->integer('for')->default(0);
            $table->integer('against')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('scoreboards');
    }
}
