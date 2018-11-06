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
        foreach ($odds as $odd) {
            $odd->cat_palpite;
            $odd->tipo_palpite;
        }
        $oddsAgrupadas = $odds->groupBy('cat_palpite_id');
        return $oddsAgrupadas;
    }
}
