<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventoBolaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evento_bolaos', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('liga_id');
            $table->integer('time1_id');
            $table->integer('time2_id');
            $table->datetime('data_evento');
        });

        Schema::table('evento_bolaos', function (Blueprint $table) {
            $table->foreign('liga_id')->references('id')->on('ligas');
            $table->foreign('time1_id')->references('id')->on('times');
            $table->foreign('time2_id')->references('id')->on('times');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evento_bolaos');
    }
}
