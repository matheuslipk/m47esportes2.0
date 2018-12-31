<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agentes', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('gerente_id');
            $table->integer('status_conta_id');
            $table->string('nickname', 20);
            $table->string('name');
            $table->string('telefone');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('agentes', function (Blueprint $table) {
            $table->foreign('id')->references('id')->on('users');
            $table->foreign('gerente_id')->references('id')->on('gerentes');
            $table->foreign('status_conta_id')->references('id')->on('status_contas');
            $table->unique('nickname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agentes');
    }
}
