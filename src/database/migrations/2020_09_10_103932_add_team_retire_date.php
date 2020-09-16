<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTeamRetireDate extends Migration
{
    public function up()
    {
        Schema::table('teams', function(Blueprint $table) {
            $table->dateTime('retired_on')
                ->nullable(true)
                ->default(null);
        });
    }

    public function down()
    {
        //
    }
}
