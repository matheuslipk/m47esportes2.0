<?php

namespace App\Http\Controllers\AuthGerente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Gerente;

class RegistroGerenteController extends Controller{

    public function __construct(){
        $this->middleware('auth:web-admin');
    }

    public function registrar(Request $request){
    	$admin = new Gerente();
    	$admin->name = $request->input('name');
        $admin->nickname = $request->input('nickname');
        $admin->telefone = $request->input('telefone');
    	$admin->email = $request->input('email');
        $admin->status_conta_id = $request->input('status_conta');
    	$admin->password =  Hash::make($request->input('password'));
    	$admin->save();
    	return redirect()->route('listagerentes');
    }

    public function showRegistroForm(){
    	return view('auth.gerenteregistro');
    }

}
