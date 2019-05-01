<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventoBolao extends Model
{
    public $timestamps = false;

    protected $table = 'evento_bolaos';

    public function bolaos(){
    	return $this->belongsToMany('App\Bolao');
    }

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
