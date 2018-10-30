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
            $table->integer('user_id')->nullable();
            $table->string('nome')->nullable();
            $table->decimal('cotacao_total',5,2);
            $table->decimal('valor_apostado',5,2);
            $table->decimal('premiacao',6,2);
            $table->integer('ganhou');
            $table->timestamp('data_aposta')->nullable();
            $table->timestamp('data_validacao')->nullable();
            $table->string('controle')->nullable();
            $table->string('identificador')->nullable();
        });

        Schema::table('apostas', function (Blueprint $table) {
            $table->foreign('agente_id')->references('id')->on('agentes');
            $table->foreign('user_id')->references('id')->on('users');
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
