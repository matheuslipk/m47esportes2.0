<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aposta extends Model
{
	public function palpites(){
		return $this->hasMany('App\Palpite');
	}
	
    public $timestamps = false;



    public static function getApostasComStatusJSON($palpitesAgrupados){
        $apostas = [];

        foreach ($palpitesAgrupados as $indexAposta => $aposta) {
            $quantPalpites = $aposta->count();
            $quantAcertos = 0;
            $quantPalpitesConferidos = 0;

            foreach ($aposta as $palpite) {
                $quantPalpitesConferidos++;

                if($palpite->situacao_palpite_id===2){
                    $aposta['status'] = 2;
                    $apostas[$indexAposta] = $aposta;
                    break;
                }

                if($palpite->situacao_palpite_id===1){
                    $quantAcertos++;
                    if($quantPalpites===$quantAcertos){
                        $aposta['status'] = 1;
                        $apostas[$indexAposta] = $aposta;
                        break;
                    }
                }

                if($palpite->situacao_palpite_id===3){
                    $aposta['status'] = 3;
                    if($quantPalpitesConferidos===$quantPalpites){
                        $apostas[$indexAposta] = $aposta;
                        break;
                    }
                }
            }
        }
        return $apostas;
    }
}
