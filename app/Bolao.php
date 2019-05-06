<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bolao extends Model{
	const STATUS_DISPONIVEL = 1;
	const STATUS_CANCELADO = 2;
	const STATUS_ANDAMENTO = 3;
	const STATUS_FINALIZADO = 4;


    public function eventosBolao(){
    	return $this->belongsToMany('App\EventoBolao');
    }

    

    public function arrecadado( $bolao_id ){
    	$filtro = [ 
    		['bolao_id', $bolao_id],
    		['agente_id', '<>', ''],
    		['status_id', 1],
    	];

    	$apostaBolaos = ApostaBolao::where($filtro)->get();

    	return $apostaBolaos->sum('valor_apostado');
    }
}
