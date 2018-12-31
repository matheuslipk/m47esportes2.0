<?php

namespace App\Http\Controllers\Agente;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\MinhaClasse;
use App\Agente;
use App\User;

class ContaAgenteController extends Controller{
	public function __construct(){
		return $this->middleware('auth');
	}

    public function showConta(Request $request){
    	$agente = Agente::find(Auth::guard('web')->user()->id);
    	return view('agente.conta', compact('agente'));
    }

    public function atualizarConta(Request $request){
        

    	$agente = Agente::find(Auth::guard('web')->user()->id);
    	$agente->telefone = $request->input('telefone');
    	$agente->email = $request->input('email');
    	$agente->save();

    	return redirect()->route('agenteconta');
    }

    public function atualizarSenha(Request $request){
        

    	$agente = Agente::find(Auth::guard('web')->user()->id);
    	if(Hash::check($request->input('senha-antiga'), $agente->password)){

            $usuario = User::find(Auth::guard('web')->user()->id);
            $usuario->password = ($request->input('nova-senha'));
            $usuario->save();

    		$agente->password = Hash::make($request->input('nova-senha'));
	    	$agente->save();
	    	return redirect()->route('agenteconta');
    	}
	    	
    	return "Senha antiga incorreta: ".$request->input('senha-antiga');
    	
    }
}
