<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    public function liga(){
    	return $this->belongsTo('App\Liga');
    }

    public function time1(){
    	return $this->belongsTo('App\Time', 'time1_id', 'id');
    }

    public function time2(){
    	return $this->belongsTo('App\Time', 'time2_id', 'id');
    }

}
