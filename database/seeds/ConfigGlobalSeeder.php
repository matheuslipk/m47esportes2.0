<?php

use Illuminate\Database\Seeder;

class ConfigGlobalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('config_globals')->insert([
            'tipo_config_id' => 1,
            'valor' => 2,
        ]);
        DB::table('config_globals')->insert([
            'tipo_config_id' => 2,
            'valor' => 800,
        ]);
        DB::table('config_globals')->insert([
            'tipo_config_id' => 3,
            'valor' => 2,
        ]);
        DB::table('config_globals')->insert([
            'tipo_config_id' => 4,
            'valor' => 200,
        ]);
        DB::table('config_globals')->insert([
            'tipo_config_id' => 5,
            'valor' => 2,
        ]);
        DB::table('config_globals')->insert([
            'tipo_config_id' => 6,
            'valor' => 8000,
        ]);
        DB::table('config_globals')->insert([
            'tipo_config_id' => 7,
            'valor' => 1,
        ]);
        DB::table('config_globals')->insert([
            'tipo_config_id' => 8,
            'valor' => 14,
        ]);
        DB::table('config_globals')->insert([
            'tipo_config_id' => 9,
            'valor' => 0.075,
        ]);
        DB::table('config_globals')->insert([
            'tipo_config_id' => 10,
            'valor' => 0.1,
        ]);
        DB::table('config_globals')->insert([
            'tipo_config_id' => 11,
            'valor' => 0.125,
        ]);
        DB::table('config_globals')->insert([
            'tipo_config_id' => 12,
            'valor' => 0.15,
        ]);
    }
}
