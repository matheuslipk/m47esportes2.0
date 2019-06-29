<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApostasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apostas', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('agente_id')->nullable();
            $table->integer('cliente_id')->nullable();
            $table->string('nome')->nullable();
            $table->decimal('cotacao_total',5,2);
            $table->decimal('valor_apostado',6,2);
            $table->decimal('comissao_agente',3,3)->nullable();
            $table->decimal('premiacao',7,2);
            $table->decimal('ganhou', 7,2);
            $table->boolean('aposta_paga')->nullable();
            $table->timestamp('data_aposta')->nullable();
            $table->timestamp('data_validacao')->nullable();
            $table->string('controle')->nullable();
            $table->string('identificador')->nullable();
        });

        Schema::table('apostas', function (Blueprint $table) {
            $table->foreign('agente_id')->references('id')->on('agentes');
            $table->foreign('cliente_id')->references('id')->on('clientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apostas');
    }
}
