<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOddsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('odds', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('evento_id');
            $table->integer('cat_palpite_id');
            $table->integer('tipo_palpite_id');
            $table->decimal('valor',5,2);
            $table->timestamps();
        });

        Schema::table('odds', function (Blueprint $table) {
            $table->foreign('evento_id')->references('id')->on('eventos');
            $table->foreign('cat_palpite_id')->references('id')->on('cat_palpites');
            $table->foreign('tipo_palpite_id')->references('id')->on('tipo_palpites');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('odds');
    }
}
