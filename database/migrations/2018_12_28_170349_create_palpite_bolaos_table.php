<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePalpiteBolaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('palpite_bolaos', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('aposta_bolao_id');
            $table->integer('bolao_id');
            $table->integer('evento_id');
            $table->integer('tipo_palpite_id');
            $table->integer('situacao_palpite_id');
        });

        Schema::table('palpite_bolaos', function (Blueprint $table) {
            $table->unique(['aposta_bolao_id', 'evento_id']);
            $table->unique(['bolao_id', 'evento_id']);

            $table->foreign('aposta_bolao_id')->references('id')->on('aposta_bolaos');
            $table->foreign('tipo_palpite_id')->references('id')->on('tipo_palpites');
            $table->foreign('situacao_palpite_id')->references('id')->on('situacao_palpites');
            $table->foreign(['bolao_id', 'evento_id'])->references(['bolao_id', 'evento_id'])->on('evento_bolaos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('palpite_bolaos');
    }
}
