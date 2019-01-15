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
    public function index(Request $request){
        $todasLigas = Liga::where('is_top_list','>=', 1)
            ->orderBy('nome')
            ->get();

        $filtro[] = ['data','>=',MinhaClasse::timestamp_to_data_mysql(time())];
        if($request->filled('data')){
            $data = date("Y-m-d", time())." 23:59:59";
            $filtro[] = ['data','<=', $data];
        }

        $eventos = Evento::where($filtro)->orderBy('data', 'asc')->get();

        $eventosAgrupados = $eventos->groupBy('liga_id');

        $arrayIdLigas = $this->getIndexsLigas($eventosAgrupados);
        $arrayIdTimes = $this->getIndexsTimes($eventos);
        $arrayIdEventos = $this->getIndexsEventos($eventos);

        $ligas = $todasLigas->whereIn('id', $arrayIdLigas);

        foreach ($ligas as $liga) {
            $liga->eventos = $eventos->where('liga_id',$liga->id);
            foreach ($liga->eventos as $evento) {
                $odds = Odd::where('evento_id', $evento->id)->get();
                $oddsPrincipais = $odds->where('cat_palpite_id', 1);

                $evento->quantOdds = $odds->count();
                $evento->odds = $oddsPrincipais->where('evento_id', $evento->id);
                $evento->time1 = Time::where('id', $evento->time1_id)->first();
                $evento->time2 = Time::where('id', $evento->time2_id)->first();
                $evento->data = MinhaClasse::data_mysql_to_datahora_formatada($evento->data);
            }
        }
        // return $ligas;
    	return view('public.index', compact('ligas'));
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
