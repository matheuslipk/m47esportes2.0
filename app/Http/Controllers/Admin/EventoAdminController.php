<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\MinhaClasse;
use App\Http\Controllers\Admin\LigaAdminController;
use Illuminate\Support\Facades\DB;
use App\Evento;
use App\Liga;
use App\Time;
use App\Odd;

class EventoAdminController extends Controller
{
    public function __construct(){
        // $this->middleware('auth:web-admin');
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
        $arrayIdEventos = $this->getIndexsEventos($eventos);

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


    public function indexJSON(){
    	$eventos = Evento::all()->take(5);
    	foreach ($eventos as $evento) {
    		$evento->liga;
    		$evento->time1;
    		$evento->time2;
    	}
    	return $eventos;
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
}
