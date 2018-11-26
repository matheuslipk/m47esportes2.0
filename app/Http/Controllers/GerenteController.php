<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GerenteController extends Controller
{
    function __construct(){
		$this->middleware('auth:gerente');
	}

    function index(){
    	return view('gerente');
    }
}
