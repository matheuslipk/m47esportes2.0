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
}
