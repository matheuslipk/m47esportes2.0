<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aposta extends Model

{

    public static function getControle($aposta_id){
       return md5($aposta_id."-m47esportes");
    }

	public function palpites(){
		return $this->hasMany('App\Palpite');
	}

    public function agente(){
        return $this->belongsTo('App\Agente');
    }

    public function cliente(){
        return $this->belongsTo('App\Cliente');
    }
	
    public $timestamps = false;

    public static function getApostasComStatusJSON2($apostasSemStatus){
        $indexApostas = Aposta::getIndexApostas($apostasSemStatus);
        $palpitesAgrupados = Palpite::whereIn('aposta_id', $indexApostas)->get()->groupBy('aposta_id');


        $apostas = [];

        foreach ($palpitesAgrupados as $indexAposta => $aposta) {
            $quantPalpites = $aposta->count();
            $quantAcertos = 0;
            $quantAnulados = 0;
            $quantPalpitesConferidos = 0;
            $apostas[$indexAposta] = $aposta;

            foreach ($aposta as $palpite) {
                $quantPalpitesConferidos++;

                if($palpite->situacao_palpite_id===2){
                    $aposta['status'] = 2;
                    break;
                }

                if($palpite->situacao_palpite_id===1){
                    $quantAcertos++;
                    if($quantPalpites===($quantAcertos + $quantAnulados)){
                        $aposta['status'] = 1;
                        break;
                    }
                }

                if($palpite->situacao_palpite_id===3){
                    $aposta['status'] = 3;
                    if($quantPalpitesConferidos===$quantPalpites){
                        break;
                    }
                }

                if($palpite->situacao_palpite_id===4){
                    $quantAnulados++;
                    if( $quantPalpites===($quantAcertos + $quantAnulados) ){
                        $aposta['status'] = 1;
                        break;
                    }
                }
            }
        }
        return $apostas;
    }

    public static function getApostasComStatusJSON($palpitesAgrupados){
        $apostas = [];

        foreach ($palpitesAgrupados as $indexAposta => $aposta) {
            $quantPalpites = $aposta->count();
            $quantAcertos = 0;
            $quantPalpitesConferidos = 0;
            $apostas[$indexAposta] = $aposta;

            foreach ($aposta as $palpite) {
                $quantPalpitesConferidos++;

                if($palpite->situacao_palpite_id===2){
                    $aposta['status'] = 2;
                    break;
                }

                if($palpite->situacao_palpite_id===1){
                    $quantAcertos++;
                    if($quantPalpites===$quantAcertos){
                        $aposta['status'] = 1;
                        break;
                    }
                }

                if($palpite->situacao_palpite_id===3){
                    $aposta['status'] = 3;
                    if($quantPalpitesConferidos===$quantPalpites){
                        break;
                    }
                }
            }
        }
        return $apostas;
    }

    public static function getIndexApostas($arrayApostas){
        $index = [];
        foreach ($arrayApostas as $aposta) {
            $index[] = $aposta->id;
        }
        return $index;
    }
}
