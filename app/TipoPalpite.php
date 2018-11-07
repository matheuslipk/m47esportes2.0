<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoPalpite extends Model
{
    public function cat_palpite(){
    	return $this->belongsTo('App\CatPalpite');
    }
}
