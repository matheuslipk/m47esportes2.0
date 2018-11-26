<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Agente;

class AgenteAdminController extends Controller
{
    public function index(Request $request){
    	$agentes = Agente::all();
    	return $agentes;
    }
}
