<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminAgentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_agentes', function (Blueprint $table) {
            $table->integer('admin_id');
            $table->integer('agente_id');
        });

        Schema::table('admin_agentes', function (Blueprint $table) {
            $table->foreign('admin_id')->references('id')->on('admins');
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
        Schema::dropIfExists('admin_agentes');
    }
}
