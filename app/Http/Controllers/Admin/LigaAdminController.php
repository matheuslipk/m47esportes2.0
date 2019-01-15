<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Liga;
use App\Evento;
use App\Pais;
use App\Http\Controllers\Api\MinhaClasse;

class LigaAdminController extends Controller
{
	public function __construct(){
        $this->middleware('auth:web-admin');
    }

    public function index(Request $request){
    	$ligas = Liga::where('is_top_list', '>=', 0)
	    	->orderBy('is_top_list', 'desc')
	    	->orderBy('nome')
	    	->get();

    	return view('admin.ligas.ligas', compact('ligas'));
    }

    public function getLiga(Request $request, $id){
    	$liga = Liga::find($id);

    	if(isset($liga)){
    		return view('admin.ligas.liga', compact('liga'));
    	}
    	return redirect()->route('adminligas');
    	
    }

    public function update(Request $request, $id){
    	$liga = Liga::find($id);

    	if(isset($liga)){
    		$liga->nome = $request->input('nome');
    		$liga->is_top_list = $request->input('prioridade');
    		$liga->save();

    		return redirect()->route('adminliga', $id);
    	}
    	return redirect()->route('adminligas');
    	
    }

    public static function getLigasByEventos($eventos){
    	
    }


}
