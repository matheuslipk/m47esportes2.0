<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('esporte_id');
            $table->timestamp('data')->nullable();
            $table->integer('status_evento_id');
            $table->integer('liga_id');
            $table->integer('time1_id');
            $table->integer('time2_id');
            $table->integer('FI_365')->nullable();
            $table->timestamps();
        });

        Schema::table('eventos', function(Blueprint $table){
            $table->foreign('esporte_id')->references('id')->on('esportes');
            $table->foreign('liga_id')->references('id')->on('ligas');
            $table->foreign('status_evento_id')->references('id')->on('status_eventos');
            $table->foreign('time1_id')->references('id')->on('times');
            $table->foreign('time2_id')->references('id')->on('times');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eventos');
    }
}
