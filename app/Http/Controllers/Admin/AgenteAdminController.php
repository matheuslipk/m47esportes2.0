<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Agente;
use App\StatusConta;

class AgenteAdminController extends Controller
{
	public function __construct(){
        $this->middleware('auth:web-admin');
    }

    public function index(Request $request){
    	$status = StatusConta::all();    	
    	$agentes = Agente::all();

    	foreach ($agentes as $agente) {
    		# code...
    	}
    	return $agentes;
    }
}
