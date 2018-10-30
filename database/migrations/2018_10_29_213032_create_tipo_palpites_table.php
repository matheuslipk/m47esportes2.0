<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoPalpitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_palpites', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('cat_palpite_id');
            $table->string('nome');
        });

        Schema::table('tipo_palpites', function (Blueprint $table) {
            $table->foreign('cat_palpite_id')->references('id')->on('cat_palpites');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_palpites');
    }
}
