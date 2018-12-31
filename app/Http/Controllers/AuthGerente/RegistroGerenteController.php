<?php

namespace App\Http\Controllers\AuthGerente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Gerente;
use App\User;

class RegistroGerenteController extends Controller{

    public function __construct(){
        $this->middleware('auth:web-admin');
    }

    public function registrar(Request $request){
        $usuario = new User();
        $usuario->name = $request->input('name');
        $usuario->nickname = $request->input('nickname')."_gerente";
        $usuario->password = ($request->input('password'));
        $usuario->save();

    	$gerente = new Gerente();
        $gerente->id = $usuario->id;
    	$gerente->name = $request->input('name');
        $gerente->nickname = $request->input('nickname');
        $gerente->telefone = $request->input('telefone');
    	$gerente->email = $request->input('email');
        $gerente->status_conta_id = $request->input('status_conta');
    	$gerente->password =  Hash::make($request->input('password'));
    	$gerente->save();
    	return redirect()->route('listagerentes');
    }

    public function showRegistroForm(){
    	return view('auth.gerenteregistro');
    }

}
