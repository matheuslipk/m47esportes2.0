<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigAgentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_agentes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tipo_config_id');
            $table->integer('agente_id');
            $table->decimal('valor', 8 , 3);
        });
        Schema::table('config_agentes', function (Blueprint $table) {
            $table->foreign('tipo_config_id')->references('id')->on('tipo_configs');
            $table->foreign('agente_id')->references('id')->on('agentes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('config_agentes');
    }
}
