<?php

use Illuminate\Database\Seeder;

class TipoConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_configs')->insert([
            'id' => 1,
            'nome' => 'Odd Mínima',
        ]);
        DB::table('tipo_configs')->insert([
            'id' => 2,
            'nome' => 'Odd Máxima',
        ]);
        DB::table('tipo_configs')->insert([
            'id' => 3,
            'nome' => 'Valor mínimo da aposta',
        ]);
        DB::table('tipo_configs')->insert([
            'id' => 4,
            'nome' => 'Valor máximo da aposta',
        ]);
        DB::table('tipo_configs')->insert([
            'id' => 5,
            'nome' => 'Premiação mínima da aposta',
        ]);
        DB::table('tipo_configs')->insert([
            'id' => 6,
            'nome' => 'Premiação máxima da aposta',
        ]);
        DB::table('tipo_configs')->insert([
            'id' => 7,
            'nome' => 'Quant mínima de palpites',
        ]);
        DB::table('tipo_configs')->insert([
            'id' => 8,
            'nome' => 'Quant máxima da palpites',
        ]);
        DB::table('tipo_configs')->insert([
            'id' => 9,
            'nome' => 'Comissão com Odds >= 2 e < 6',
        ]);
        DB::table('tipo_configs')->insert([
            'id' => 10,
            'nome' => 'Comissão com Odds >= 6 e < 12',
        ]);
        DB::table('tipo_configs')->insert([
            'id' => 11,
            'nome' => 'Comissão com Odds >= 12 e < 20',
        ]);
        DB::table('tipo_configs')->insert([
            'id' => 12,
            'nome' => 'Comissão com Odds >= 20',
        ]);
        DB::table('tipo_configs')->insert([
            'id' => 13,
            'nome' => 'Tempo de atualização das Odds em minutos',
        ]);
    }
}
