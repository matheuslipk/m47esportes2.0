<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Odd;

class Evento extends Model
{
    public static function getStatusEventos(){
        $statusEvento[0] = "Não iniciado";
        $statusEvento[1] = "Ao vivo";
        $statusEvento[2] = "Em Espera";
        $statusEvento[3] = "Finalizado";
        $statusEvento[4] = "Adiado";
        $statusEvento[5] = "Cancelado";
        $statusEvento[7] = "Interrompido";
        $statusEvento[8] = "Abandonado";
        $statusEvento[99] = "Removido";

        return $statusEvento;
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

    public function odd($evento_id, $tipo_id){
    	$odd = Odd::where([
    		['evento_id',$evento_id],
    		['tipo_palpite_id', $tipo_id],
    	])->first();
    	return $odd['valor'];
    }

    public function odds(){
    	return $this->hasMany('App\Odd');
    }

    public function quantOdds($evento_id){
        $odd = Odd::where([
            ['evento_id',$evento_id],
        ])->count();

        return $odd;
    }

    public function oddsPrincipais($evento_id){
    	$odds = Odd::where([
    		['evento_id',$evento_id],
    		['cat_palpite_id',1],
    	])->get();
    	return $odds;
    }

}
