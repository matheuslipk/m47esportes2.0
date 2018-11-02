<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\MinhaClasse;
use App\Http\Controllers\Admin\LigaAdminController;
use Illuminate\Support\Facades\DB;
use App\Evento;
use App\Liga;

class EventoAdminController extends Controller
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
        // return $ligas;

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
}
