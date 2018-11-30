<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Agente;
use App\Gerente;
use App\StatusConta;

class GerenteAdminController extends Controller
{
	public function __construct(){
        $this->middleware('auth:web-admin');
    }

    public function index(Request $request){
    	$status_contas = StatusConta::all();
    	$gerentes = Gerente::orderBy('name')->get();		

    	foreach ($gerentes as $gerente) {
    		$gerente->status_conta = $status_contas->find($gerente->status_conta_id);
    	}
    	return view('admin.gerentes.listagerentes', compact('gerentes'));
    }

    public function editar(Request $request, $id){
    	$gerente = Gerente::find($id);
    	if(isset($gerente)){
            $agentes = Agente::where('gerente_id', $id)->get();
    		return view('admin.gerentes.vergerente', compact('gerente', 'agentes'));
    	}else{
    		return [];
    	}
    	
    }

    public function salvar(Request $request, $id){
    	if($id != $request->input('gerente_id')){
    		return "Erro de id diferente";
    	}

    	$gerente = Gerente::find($id);
    	if(isset($gerente)){
    		$gerente->name = $request->input('name');
    		$gerente->email = $request->input('email');
    		$gerente->status_conta_id = $request->input('status_conta');
    		$gerente->save();

    		return redirect()->route('editargerente', ['id' => $id]);
    	}else{
    		return [];
    	}
    	
    }
}
