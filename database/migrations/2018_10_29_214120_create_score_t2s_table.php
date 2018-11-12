<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoreT2sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('score_t2s', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('evento_id')->unique();
            $table->integer('score_t1');
            $table->integer('score_t2');
        });

        Schema::table('score_t2s', function (Blueprint $table) {
            $table->foreign('evento_id')->references('id')->on('eventos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('score_t2s');
    }
}
