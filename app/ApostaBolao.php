<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApostaBolao extends Model{
	const STATUS_VALIDO = 1;
	const STATUS_CANCELADO = 4;

    public $timestamps = false;

    public function agente(){
        return $this->belongsTo('App\Agente');
    }
}
