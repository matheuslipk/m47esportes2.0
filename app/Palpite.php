<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Palpite extends Model
{
    public $timestamps = false;

    public function evento(){
    	return $this->belongsTo('App\Evento');
    }
    public function tipo_palpite(){
    	return $this->belongsTo('App\TipoPalpite');
    }

}
