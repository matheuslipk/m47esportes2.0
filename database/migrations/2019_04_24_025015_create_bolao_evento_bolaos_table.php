<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBolaoEventoBolaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bolao_evento_bolao', function (Blueprint $table) {
            $table->integer('bolao_id');
            $table->integer('evento_bolao_id');
        });

        Schema::table('bolao_evento_bolao', function (Blueprint $table) {
            $table->primary(['bolao_id', 'evento_bolao_id']);

            $table->foreign('bolao_id')->references('id')->on('bolaos');
            $table->foreign('evento_bolao_id')->references('id')->on('evento_bolaos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bolao_evento_bolao');
    }
}
