<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PalpiteBolao extends Model
{
	public function evento(){
    	return $this->belongsTo('App\EventoBolao');
    }

    public $timestamps = false;
}
