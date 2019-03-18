<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventoBolao extends Model
{
    public $timestamps = false;

    public function evento(){
    	return $this->belongsTo('App\Evento');
    }
}
