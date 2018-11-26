<?php

namespace App\Http\Controllers\AuthGerente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Gerente;

class RegistroGerenteController extends Controller
{
    public function registrar(Request $request){
    	$admin = new Gerente();
    	$admin->name = $request->input('name');
    	$admin->email = $request->input('email');
    	$admin->password =  Hash::make($request->input('password'));
    	$admin->save();
    	return redirect('/gerente');
    }

    public function showRegistroForm(){
    	return view('auth.gerenteregistro');
    }

}
