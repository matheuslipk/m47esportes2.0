<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoConfig extends Model
{
    const ODD_MINIMA = 1;
    const ODD_MAXIMA = 2;
    const VALOR_MIN_APOSTA = 3;
    const VALOR_MAX_APOSTA = 4;
    const PREMIACAO_MIN_APOSTA = 5;
    const PREMIACAO_MAX_APOSTA = 6;
    const QUANT_MIN_PALPITES = 7;
    const QUANT_MAX_PALPITES = 8;

    const COMISSAO_1 = 9;
    const COMISSAO_2 = 10;
    const COMISSAO_3 = 11;
    const COMISSAO_4 = 12;

    const TEMPO_AT_ODDS = 13;
    const LIM_APOSTAS_7_DIAS = 14;
}
