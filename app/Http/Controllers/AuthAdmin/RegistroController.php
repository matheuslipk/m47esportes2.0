<?php

namespace App\Http\Controllers\AuthAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin;

class RegistroController extends Controller
{
    public function registrar(Request $request){
    	$admin = new Admin();
    	$admin->name = $request->input('name');
    	$admin->email = $request->input('email');
    	$admin->password = $request->input('password');

    	$admin->save();
    	return redirect('');
    }

    public function showRegistroForm(){
    	return view('auth.adminregistro');
    }
}
