<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Liga;
use App\Evento;
use App\Http\Controllers\Api\MinhaClasse;

class LigaAdminController extends Controller
{
	public function __construct(){
        $this->middleware('auth:web-admin');
    }

    public static function getLigasByEventos($eventos){
    	
    }
}
