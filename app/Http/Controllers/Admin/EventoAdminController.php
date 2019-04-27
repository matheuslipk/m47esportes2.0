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
use App\Palpite;
use App\Aposta;

class EventoAdminController extends Controller
{
    public function __construct(){
        $this->middleware('auth:web-admin');
    }

    public function index(){
        $todasLigas = Liga::where('is_top_list', '>=', 1)
            ->orderBy('is_top_list', 'desc')
            ->orderBy('nome')
            ->get();

        $eventos = Evento::where([
            ['data','>=',MinhaClasse::timestamp_to_data_mysql(time())]
        ])->orderBy('data', 'asc')
        ->get();

        $eventosAgrupados = $eventos->groupBy('liga_id');

        $arrayIdLigas = $this->getIndexsLigas($eventosAgrupados);
        $arrayIdTimes = $this->getIndexsTimes($eventos);

        $times = Time::find($arrayIdTimes);
        $ligas = $todasLigas->whereIn('id', $arrayIdLigas);

        foreach ($ligas as $liga) {
            $liga->eventos = $eventos->where('liga_id',$liga->id);
            foreach ($liga->eventos as $evento) {
                $odds = Odd::where('evento_id', $evento->id)->get();

                $evento->quantOdds = $odds->count();
                $evento->time1 = Time::where('id', $evento->time1_id)->first();
                $evento->time2 = Time::where('id', $evento->time2_id)->first();
                $evento->data = MinhaClasse::data_mysql_to_datahora_formatada($evento->data);
            }
        }

        return view('admin.eventos', compact('ligas'));
    }


    public function showAtualizarResultadoEventos(Request $request){
        $statusEvento = Evento::getStatusEventos();
        return view('admin.atualizareventos', compact('statusEvento'));
    }

    public function showEditarEventos(Request $request){
        $request->validate([
            'evento_id' => 'required|integer'
        ]);
        $evento = Evento::find($request->evento_id);
        $evento->time1;
        $evento->time2;
        $evento->scores;
        $evento->scores_t1;
        $evento->scores_t2;
        return view('admin.editarevento', compact('evento'));
    }

    public function editarEventos(Request $request){
        $request->validate([
            'evento_id' => 'required|integer'
        ]);

        $score = Score::where('evento_id', $request->evento_id)->first();
        $score1t = ScoreT1::where('evento_id', $request->evento_id)->first();
        $score2t = ScoreT2::where('evento_id', $request->evento_id)->first();

        if( isset($score) ){
            $score->score_t1 = $request->gols_casa;
            $score->score_t2 = $request->gols_fora;
            $score->save();
        }else{
            $score = new Score();
            $score->score_t1 = $request->gols_casa;
            $score->score_t2 = $request->gols_fora;
            $score->save();
        }

        if( isset($score1t) ){
            $score1t->score_t1 = $request->gols_casa_1t;
            $score1t->score_t2 = $request->gols_fora_1t;
            $score1t->save();
        }else{
            $score = new Score();
            $score->score_t1 = $request->gols_casa_1t;
            $score->score_t2 = $request->gols_fora_1t;
            $score->save();
        }

        if( isset($score2t) ){
            $score2t->score_t1 = $request->gols_casa_2t;
            $score2t->score_t2 = $request->gols_fora_2t;
            $score2t->save();
        }else{
            $score = new Score();
            $score->score_t1 = $request->gols_casa_2t;
            $score->score_t2 = $request->gols_fora_2t;
            $score->save();
        }

        return back();
    }

    public function showCadastrarEventos(Request $request){
        return view('admin.cadastrareventos');
    }

    public function getEventosJSON(Request $request){        
        
        if($request->filled('dataFim')){
            $dataInicio = $request->input('dataInicio');
        }else{
            $dataInicio = MinhaClasse::timestamp_to_data_mysql(time());
        }

        $filtro = [
            ['data','>=',$dataInicio],        
        ];

        if($request->filled('dataFim')){
            $filtro[] = ['data','<=', $request->input('dataFim')];
        }

        if($request->filled('liga_id')){
            $filtro[] = ['liga_id', $request->input('liga_id')];
        }

        if($request->filled('statusEvento')){
            if( $request->input('statusEvento') !== 'todos' ){
                $filtro[] = ['status_evento_id', intval( $request->input('statusEvento') )];
            }
        }

        $eventos = Evento::whereIn('liga_id', Liga::getIdProxLigas() )
            ->where($filtro)
            ->orderBy('data', 'desc')
            ->take(100)
            ->get();

        $arrayIdTimes = $this->getIndexsTimes($eventos);

        $times = Time::find($arrayIdTimes);

        $statusEvento = Evento::getStatusEventos();

        foreach ($eventos as $evento) {
            $evento->liga;
            $evento->status_evento_name = $statusEvento[$evento->status_evento_id];
            $evento->data = MinhaClasse::data_mysql_to_datahora_formatada($evento->data);
            $evento->time1 = $times->where('id', $evento->time1_id)->first();
            $evento->time2 = $times->where('id', $evento->time2_id)->first();
        }

        return $eventos;

    }

    public  function atualizarEventoApi(Request $request){
        $eventoApi = new EventosApi();
        $resposta = $eventoApi->resultado($request);
        $arrayEvento = json_decode($resposta, true);
        $statusEventoApi = $arrayEvento['results'][0]['time_status'];
        $evento = Evento::find($request->input('event_id'));

        // return $statusEventoApi;

        if(isset($evento)){
            $evento->data = MinhaClasse::timestamp_to_data_mysql($arrayEvento['results'][0]['time']);
            $evento->status_evento_id = $arrayEvento['results'][0]['time_status'];
            $evento->save();
        }

        if(($statusEventoApi==3 || $statusEventoApi==99)  && isset($arrayEvento['results'][0]['scores'][1])){
            $this->atualizarPrimeiroTempo($evento->id, $arrayEvento);
        }
        if(($statusEventoApi==3 || $statusEventoApi==99)  && isset($arrayEvento['results'][0]['scores'][2])){
            $this->atualizarSegundoTempo($evento->id, $arrayEvento);
            $this->atualizarResultadoFinal($evento->id, $arrayEvento);
        }elseif(($statusEventoApi==3 || $statusEventoApi==99)){
            $this->atualizarResultadoFinal2($evento->id, $arrayEvento);
        }

        $statusEvento = Evento::getStatusEventos();
        
        return "Evento ".$statusEvento[$evento->status_evento_id];
    }

    public function anularevento($evento_id){
        $evento = Evento::find($evento_id);
        if(isset($evento)){
            $evento->status_evento_id = 9;
            $evento->save();

            Score::where('evento_id', $evento_id)->delete();
            ScoreT1::where('evento_id', $evento_id)->delete();
            ScoreT2::where('evento_id', $evento_id)->delete();
            Palpite::where('evento_id', $evento->id)->update(['situacao_palpite_id' => 4]);

            $palpites = Palpite::where('evento_id', $evento->id)->get();
            foreach ($palpites as $palpite) {
                $aposta = Aposta::find($palpite->aposta_id);
                if(isset($aposta)){
                    $aposta->premiacao = ($aposta->premiacao/$palpite->cotacao);
                    $aposta->save();
                }
            }


        }
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
            $this->apagarOddsEvento($evento_id);
        }else{
            $score = new Score();
            $score->evento_id = $evento_id;
            $score->score_t1 = $arrayEvento['results'][0]['scores'][2]['home'];
            $score->score_t2 = $arrayEvento['results'][0]['scores'][2]['away'];
            $score->save();
            $this->apagarOddsEvento($evento_id);
        } 
    }

    private function atualizarResultadoFinal2($evento_id, $arrayEvento){
        $score = Score::where('evento_id', $evento_id)->first();
        if($arrayEvento['results'][0]['ss'] == null){
            return;
        }
        $scores = explode("-", $arrayEvento['results'][0]['ss']);
        $time1 = $scores[0];
        $time2 = $scores[1];

        if(isset($score)){
            $score->evento_id = $evento_id;
            $score->score_t1 = $time1;
            $score->score_t2 = $time2;
            $score->save();
            $this->apagarOddsEvento($evento_id);
        }else{
            $score = new Score();
            $score->evento_id = $evento_id;
            $score->score_t1 = $time1;
            $score->score_t2 = $time2;
            $score->save();
            $this->apagarOddsEvento($evento_id);
        } 
    }

    private function apagarOddsEvento($evento_id){
        Odd::where('evento_id', $evento_id)->delete();
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
