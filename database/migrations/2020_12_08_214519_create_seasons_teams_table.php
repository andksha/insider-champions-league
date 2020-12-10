<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeasonsTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seasons_teams', function (Blueprint $table) {
            $table->id();

            $table->integer('season_id');
            $table->integer('team_id');
//            $table->integer('pts');
//            $table->integer('plays');
//            $table->integer('wins');
//            $table->integer('loses');
//            $table->integer('draws');
//            $table->integer('goal_difference');

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
        Schema::dropIfExists('seasons_teams');
    }
}
