<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoreBolaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('score_bolaos', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('evento_id')->unique();
            $table->integer('score_time1');
            $table->integer('score_time2');
        });

        Schema::table('score_bolaos', function (Blueprint $table) {
            $table->foreign('evento_id')->references('id')->on('evento_bolaos');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('score_bolaos');
    }
}
