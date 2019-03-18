<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bolao extends Model
{
    public function eventosBolao(){
    	return $this->hasMany('App\EventoBolao');
    }
}
