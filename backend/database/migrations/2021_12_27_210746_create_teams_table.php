<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->dateTime('start')->nullable(true);
            $table->dateTime('end')->nullable(true);
            $table->string('name')->unique();
            $table->string('slug')->unique();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('teams');
    }
}
