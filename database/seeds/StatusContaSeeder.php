<?php

use Illuminate\Database\Seeder;

class StatusContaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('status_contas')->insert([
            'id' => 1,
            'nome' => 'Ativa',
        ]);

        DB::table('status_contas')->insert([
            'id' => 2,
            'nome' => 'Inativa',
        ]);

        DB::table('status_contas')->insert([
            'id' => 3,
            'nome' => 'Suspensa',
        ]);

        DB::table('status_contas')->insert([
            'id' => 4,
            'nome' => 'Limitada',
        ]);
    }
}
