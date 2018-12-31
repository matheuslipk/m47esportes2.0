<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CatPalpite;
use App\TipoPalpite;
use App\Liga;
use App\Evento;
use App\Odd;
use App\Time;

use App\Http\Controllers\Api\MinhaClasse;

class EventoController extends Controller
{
    public function index(){
        $todasLigas = Liga::where('is_top_list',1)
            ->orderBy('nome')
            ->get();

        $eventos = Evento::where([
            ['data','>=',MinhaClasse::timestamp_to_data_mysql(time())]
        ])->orderBy('data', 'asc')
        ->get();

        $eventosAgrupados = $eventos->groupBy('liga_id');

        $arrayIdLigas = $this->getIndexsLigas($eventosAgrupados);
        $arrayIdTimes = $this->getIndexsTimes($eventos);
        $arrayIdEventos = $this->getIndexsEventos($eventos);

        $times = Time::find($arrayIdTimes);
        $ligas = $todasLigas->whereIn('id', $arrayIdLigas);

        $odds = Odd::whereIn('evento_id', $arrayIdEventos)
            ->orderBy('evento_id')
            ->get();

        $oddsPrincipais = $odds->where('cat_palpite_id', 1);

        // return $oddsPrincipais;

        foreach ($ligas as $liga) {
            $liga->eventos = $eventos->where('liga_id',$liga->id);
            foreach ($liga->eventos as $evento) {
                $evento->quantOdds = $odds->where('evento_id', $evento->id)->count();
                $evento->odds = $oddsPrincipais->where('evento_id', $evento->id);
                $evento->time1 = $times->where('id', $evento->time1_id)->first();
                $evento->time2 = $times->where('id', $evento->time2_id)->first();
                $evento->data = MinhaClasse::data_mysql_to_datahora_formatada($evento->data);
            }
        }
        // return $ligas;
    	return view('public.index', compact(['ligas']));
    }

    public function getIndexsLigas($array){
        $arrayIndex = [];
        foreach ($array as $key => $a) {
            $arrayIndex[] = $key;
        }
        return $arrayIndex;
    }

    public function getIndexsTimes($eventos){
        $arrayIndex = [];
        foreach ($eventos as $evento) {
            $arrayIndex[] = $evento->time1_id;
            $arrayIndex[] = $evento->time2_id;
        }
        return $arrayIndex;
    }

    public function getIndexsEventos($eventos){
        $arrayIndex = [];
        foreach ($eventos as $evento) {
            $arrayIndex[] = $evento->id;
        }
        return $arrayIndex;
    }

    public function getOdds($evento_id){
        $odds = Odd::where('evento_id', $evento_id)->get();
        $contem = $this->contemPalpiteSelecionadoDoEvento($evento_id);
        $catPalpites = CatPalpite::all();
        $tipoPalpites = TipoPalpite::all();

        if($contem['resultado']){            
            foreach ($odds as $odd) {
                $odd->cat_palpite = $catPalpites->where('id', $odd->cat_palpite_id)->first();
                $odd->tipo_palpite = $tipoPalpites->where('id', $odd->tipo_palpite_id)->first();
                if($odd->tipo_palpite_id == $contem['tipo_palpite'])
                    $odd->selecionado=true;
            }
        }else{
            foreach ($odds as $odd) {
                $odd->cat_palpite = $catPalpites->where('id', $odd->cat_palpite_id)->first();
                $odd->tipo_palpite = $tipoPalpites->where('id', $odd->tipo_palpite_id)->first();
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
