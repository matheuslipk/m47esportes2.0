<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigGlobalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_globals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tipo_config_id');
            $table->decimal('valor', 8 , 3);
        });
        Schema::table('config_globals', function (Blueprint $table) {
            $table->foreign('tipo_config_id')->references('id')->on('tipo_configs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('config_globals');
    }
}
