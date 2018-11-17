<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Aposta;

class ApostaAdminController extends Controller{

	 public function __construct(){
        $this->middleware('auth:web-admin');
    }

    public function apostas(Request $request){
    	$apostas = Aposta::where([
    		['agente_id', "<>", '']
    	])
    	->take(30)
    	->orderBy('id', 'desc')
    	->get();

    	return view('admin.apostas', compact('apostas'));
    }

    public function apostasJSON(Request $request){
        $dataInico = $request->input('data_inicio');
        $dataFinal = $request->input('data_final');

        $apostas = Aposta::where([
            ['agente_id', "<>", ''],
            ['data_aposta', ">=", $dataInico."T00:00"],
            ['data_aposta', "<=", $dataFinal."T00:00"],
        ])
        ->take(50)
        ->orderBy('id', 'desc')
        ->get();

        return $apostas;
    }
}
