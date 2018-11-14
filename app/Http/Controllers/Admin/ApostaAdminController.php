<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ApostaAdminController extends Controller{

	 public function __construct(){
        $this->middleware('auth:web-admin');
    }

    public function apostas(Request $request){
    	$g = Auth::guard('web-admin')->get();
    	return var_dump($g);
    }
}
