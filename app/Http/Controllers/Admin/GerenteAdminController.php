<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Gerente;
use App\StatusConta;

class GerenteAdminController extends Controller
{
	public function __construct(){
        $this->middleware('auth:web-admin');
    }

    public function index(Request $request){
    	if($request->filled('todos')){
    		$status_contas = StatusConta::all();
	    	$gerentes = Gerente::all();
    	}else{
    		$status_contas = StatusConta::all();
	    	$gerentes = Gerente::where('status_conta_id', 1)->get();
    	}

	    	


    	foreach ($gerentes as $gerente) {
    		$gerente->status_conta = $status_contas->find($gerente->status_conta_id);
    	}
    	return view('admin.gerentes', compact('gerentes'));
    }
}
