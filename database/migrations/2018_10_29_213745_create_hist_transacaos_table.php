<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistTransacaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hist_transacaos', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('transacao_id');
            $table->integer('status_transacao_id');
            $table->timestamps();
        });

        Schema::table('hist_transacaos', function (Blueprint $table){
            $table->foreign('transacao_id')->references('id')->on('transacaos');
            $table->foreign('status_transacao_id')->references('id')->on('status_transacaos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hist_transacaos');
    }
}
