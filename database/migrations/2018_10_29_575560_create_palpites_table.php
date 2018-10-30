<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePalpitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('palpites', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('aposta_id');
            $table->integer('tipo_palpite_id');
            $table->decimal('cotacao',5,2);
            $table->integer('situacao_palpite_id');
        });

        Schema::table('palpites', function (Blueprint $table) {
            $table->foreign('aposta_id')->references('id')->on('apostas');
            $table->foreign('tipo_palpite_id')->references('id')->on('tipo_palpites');
            $table->foreign('situacao_palpite_id')->references('id')->on('situacao_palpites');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('palpites');
    }
}
