<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenssagemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menssagems', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('usuario_remetente');
            $table->integer('usuario_destino');
            $table->text('mensagem');
            $table->timestamps();
        });

        Schema::table('menssagems', function (Blueprint $table) {
            $table->foreign('usuario_remetente')->references('id')->on('users');
            $table->foreign('usuario_destino')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menssagems');
    }
}
