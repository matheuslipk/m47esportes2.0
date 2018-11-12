<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\MinhaClasse;
use App\Http\Controllers\Api\EventosApi;
use App\Http\Controllers\Admin\LigaAdminController;
use Illuminate\Support\Facades\DB;
use App\Evento;
use App\Liga;
use App\Time;
use App\Odd;
use App\Score;
use App\ScoreT1;
use App\ScoreT2;

class EventoAdminController extends Controller
{
    public function __construct(){
        $this->middleware('auth:web-admin');
    }

    public function index(){
        $todasLigas = Liga::where('is_top_list',1)
            ->orderBy('nome')
            ->get();

        $eventos = Evento::where([
            ['data','>=',MinhaClasse::timestamp_to_data_mysql(time())]
        ])->get();

        $eventosAgrupados = $eventos->groupBy('liga_id');

        $arrayIdLigas = $this->getIndexsLigas($eventosAgrupados);
        $arrayIdTimes = $this->getIndexsTimes($eventos);

        $times = Time::find($arrayIdTimes);
        $ligas = $todasLigas->whereIn('id', $arrayIdLigas);

        foreach ($ligas as $liga) {
            $liga->eventos = $eventos->where('liga_id',$liga->id);
            foreach ($liga->eventos as $evento) {
                $evento->time1 = $times->where('id', $evento->time1_id)->first();
                $evento->time2 = $times->where('id', $evento->time2_id)->first();
            }
        }

        return view('admin.eventos', compact('ligas'));
    }


    public function showAtualizarResultadoEventos(Request $request){
        return view('admin.atualizareventos', compact('eventos'));
    }

    public function getEventosJSON(Request $request){
        $dataInicio = $request->input('dataInicio');
        $dataFim = $request->input('dataFim');
        $statusEvento = $request->input('statusEvento');

        $eventos = Evento::where([
            ['data','>=',$dataInicio],
            ['data','<=',$dataFim],
            ['status_evento_id',$statusEvento],
        ])
        ->take(50)
        ->get();

        $arrayIdTimes = $this->getIndexsTimes($eventos);

        $times = Time::find($arrayIdTimes);

        foreach ($eventos as $evento) {
            $evento->time1 = $times->where('id', $evento->time1_id)->first();
            $evento->time2 = $times->where('id', $evento->time2_id)->first();
        }

        return $eventos;

    }

    public  function atualizarEventoApi(Request $request){
        $eventoApi = new EventosApi();
        $resposta = $eventoApi->resultado($request);
        $arrayEvento = json_decode($resposta, true);
        $evento = Evento::find($request->input('event_id'));

        if(isset($evento)){
            $evento->data = MinhaClasse::timestamp_to_data_mysql($arrayEvento['results'][0]['time']);
            $evento->status_evento_id = $arrayEvento['results'][0]['time_status'];
            $evento->save();
        }

        if($evento->status_evento_id==3 && isset($arrayEvento['results'][0]['scores'][1])){
            $this->atualizarPrimeiroTempo($evento->id, $arrayEvento);
        }
        if($evento->status_evento_id==3 && isset($arrayEvento['results'][0]['scores'][2])){
            $this->atualizarSegundoTempo($evento->id, $arrayEvento);
            $this->atualizarResultadoFinal($evento->id, $arrayEvento);
        }
        
        return $evento;
    }



    //privates

    private function atualizarPrimeiroTempo($evento_id, $arrayEvento){
        $scoreT1 = ScoreT1::where('evento_id', $evento_id)->first();
        if(isset($scoreT1)){
            $scoreT1->evento_id = $evento_id;
            $scoreT1->score_t1 = $arrayEvento['results'][0]['scores'][1]['home'];
            $scoreT1->score_t2 = $arrayEvento['results'][0]['scores'][1]['away'];
            $scoreT1->save();
        }else{
            $scoreT1 = new ScoreT1();
            $scoreT1->evento_id = $evento_id;
            $scoreT1->score_t1 = $arrayEvento['results'][0]['scores'][1]['home'];
            $scoreT1->score_t2 = $arrayEvento['results'][0]['scores'][1]['away'];
            $scoreT1->save();
        } 
    }

    private function atualizarSegundoTempo($evento_id, $arrayEvento){
        $scoreT2 = ScoreT2::where('evento_id', $evento_id)->first();
        $gols90MinT1 = $arrayEvento['results'][0]['scores'][2]['home'];
        $gols90MinT2 = $arrayEvento['results'][0]['scores'][2]['away'];
        $gols45MinT1 = $arrayEvento['results'][0]['scores'][1]['home'];
        $gols45MinT2 = $arrayEvento['results'][0]['scores'][1]['away'];

        if(isset($scoreT2)){
            $scoreT2->evento_id = $evento_id;
            $scoreT2->score_t1 = $gols90MinT1 - $gols45MinT1;
            $scoreT2->score_t2 = $gols90MinT2 - $gols45MinT2;
            $scoreT2->save();
        }else{
            $scoreT2 = new ScoreT2();
            $scoreT2->evento_id = $evento_id;
            $scoreT2->score_t1 = $gols90MinT1 - $gols45MinT1;
            $scoreT2->score_t2 = $gols90MinT2 - $gols45MinT2;
            $scoreT2->save();
        } 
    }

    private function atualizarResultadoFinal($evento_id, $arrayEvento){
        $score = Score::where('evento_id', $evento_id)->first();

        if(isset($score)){
            $score->evento_id = $evento_id;
            $score->score_t1 = $arrayEvento['results'][0]['scores'][2]['home'];
            $score->score_t2 = $arrayEvento['results'][0]['scores'][2]['away'];
            $score->save();
        }else{
            $score = new Score();
            $score->evento_id = $evento_id;
            $score->score_t1 = $arrayEvento['results'][0]['scores'][2]['home'];
            $score->score_t2 = $arrayEvento['results'][0]['scores'][2]['away'];
            $score->save();
        } 
    }

    private function getIndexsLigas($array){
        $arrayIndex = [];
        foreach ($array as $key => $a) {
            $arrayIndex[] = $key;
        }
        return $arrayIndex;
    }

    private function getIndexsTimes($eventos){
        $arrayIndex = [];
        foreach ($eventos as $evento) {
            $arrayIndex[] = $evento->time1_id;
            $arrayIndex[] = $evento->time2_id;
        }
        return $arrayIndex;
    }

    private function getIndexsEventos($eventos){
        $arrayIndex = [];
        foreach ($eventos as $evento) {
            $arrayIndex[] = $evento->id;
        }
        return $arrayIndex;
    }
}
