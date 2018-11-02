<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Liga extends Model
{
    public function eventos(){
    	return $this->hasMany('App\Evento');
    }

    public function pais(){
    	return $this->belongsTo('App\Pais', 'cc', 'cc');
    }
}
