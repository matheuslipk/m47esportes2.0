<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventoBolao extends Model
{
    public $timestamps = false;

    public function bolaos(){
    	return $this->belongsToMany('App\Bolao');
    }
}
