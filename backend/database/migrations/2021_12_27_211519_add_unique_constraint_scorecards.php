<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraintScorecards extends Migration
{
    public function up()
    {
        Schema::table('scorecards', function (Blueprint $table) {
            $table->unique(['date_played', 'home_team', 'away_team'], 'unique_play_date');
        });
    }

    public function down()
    {
        Schema::table('scorecards', function (Blueprint $table) {
            $table->dropUnique('unique_play_date');
        });
    }
}
