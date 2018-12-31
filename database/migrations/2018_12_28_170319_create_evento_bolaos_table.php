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
            $table->integer('bolao_id');
            $table->integer('evento_id');
        });

        Schema::table('evento_bolaos', function (Blueprint $table) {
            $table->unique(['bolao_id', 'evento_id']);
            $table->foreign('bolao_id')->references('id')->on('bolaos');
            $table->foreign('evento_id')->references('id')->on('eventos');
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
