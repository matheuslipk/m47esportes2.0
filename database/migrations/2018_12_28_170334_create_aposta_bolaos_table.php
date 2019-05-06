<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApostaBolaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aposta_bolaos', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('bolao_id');
            $table->integer('agente_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('nome')->nullable();
            $table->integer('quant_acertos')->default(0);
            $table->integer('quant_erros')->default(0);
            $table->decimal('valor_apostado', 6,2);
            $table->dateTime('data_criacao');
            $table->dateTime('data_validacao');
            $table->integer('status_id');
        });

        Schema::table('aposta_bolaos', function (Blueprint $table) {
            $table->foreign('bolao_id')->references('id')->on('bolaos');
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
        Schema::dropIfExists('aposta_bolaos');
    }
}
