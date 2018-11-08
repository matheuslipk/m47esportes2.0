<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aposta extends Model
{
	public function palpites(){
		return $this->hasMany('App\Palpite');
	}
	
    public $timestamps = false;
}
