<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrarsTable extends Migration
{
    public function up()
    {
        Schema::create('registrars', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('email')->unique();
        });
    }

    public function down()
    {
        Schema::dropIfExists('registrars');
    }
}
