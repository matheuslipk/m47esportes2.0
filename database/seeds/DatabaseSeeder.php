<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(StatusContaSeeder::class);
        $this->call(TipoConfigSeeder::class);
        $this->call(ConfigGlobalSeeder::class);
    }
}
