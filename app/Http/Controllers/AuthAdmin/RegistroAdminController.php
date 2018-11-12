<?php

namespace App\Http\Controllers\AuthAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Admin;

class RegistroAdminController extends Controller
{
    public function registrar(Request $request){
    	$admin = new Admin();
    	$admin->name = $request->input('name');
    	$admin->email = $request->input('email');
    	$admin->password =  Hash::make($request->input('password'));
    	$admin->save();
    	return redirect('/admin/eventos');
    }

    public function showRegistroForm(){
    	return view('auth.adminregistro');
    }
}
