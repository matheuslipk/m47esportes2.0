<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Odd;

class OddsAdminController extends Controller{

	public function __construct(){
        $this->middleware('auth:web-admin');
    }

    public function removerOddsByEvento(Request $request){
        Odd::where('evento_id', $request->input('evento_id'))->delete();
    }

}
