<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Liga;
use App\Evento;
use App\Odd;
use App\Http\Controllers\Api\MinhaClasse;

class EventoController extends Controller
{
    public function index(){
        $ligas = Liga::where('is_top_list',1)
            ->orderBy('nome')
            ->get();

        foreach ($ligas as $liga) {
            $liga->eventos = Evento::where([
                ['liga_id',$liga->id],
                ['data','>=',MinhaClasse::timestamp_to_data_mysql(time())],             
            ])->get();
        }

    	return view('index', compact('ligas'));
    }

    public function getOdds($evento_id){
        $odds = Odd::where('evento_id', $evento_id)->get();
        $contem = $this->contemPalpiteSelecionadoDoEvento($evento_id);

        if($contem['resultado']){            
            foreach ($odds as $odd) {
                $odd->cat_palpite;
                $odd->tipo_palpite;
                if($odd->tipo_palpite_id == $contem['tipo_palpite'])
                    $odd->selecionado=true;
            }
        }else{
            foreach ($odds as $odd) {
                $odd->cat_palpite;
                $odd->tipo_palpite;
            }
        }
            
        $oddsAgrupadas = $odds->groupBy('cat_palpite_id');
        return $oddsAgrupadas;
    }

    private function contemPalpiteSelecionadoDoEvento($evento_id){
        //Inicioando variavel
        $contem = [
            'resultado' => false
        ];
        //Pegando palpites na sessão
        $palpites = session('palpites');
        
        if(is_array($palpites))
        //Verificando se existe o $evento_id salvo na sessao 
        foreach ($palpites as $palpite) {
            if ($palpite['evento_id']==$evento_id) {
                $contem = [
                    'resultado' => true,
                    'tipo_palpite' => $palpite['tipo_palpite']->id,
                ];
                break;
            }
        }
        return $contem;
    }
}
