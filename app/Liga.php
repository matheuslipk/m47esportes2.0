<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Liga extends Model
{
	public $timestamps = false;
	
    public function eventos(){
    	return $this->hasMany('App\Evento');
    }

    public function pais(){
    	return $this->belongsTo('App\Pais', 'cc', 'cc');
    }

    public static function getIdProxLigas(){
    	$ligas = DB::table('ligas')
    		->where('is_top_list', '>=', 1)
    		->orderBy('is_top_list', 'desc')
            ->orderBy('nome')
    		->pluck('id');
    	return $ligas;
    }

}
