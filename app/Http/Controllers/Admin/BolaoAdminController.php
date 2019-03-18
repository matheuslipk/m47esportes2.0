<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Bolao;
use App\Liga;
use App\EventoBolao;
use App\Http\Controllers\Api\MinhaClasse;

class BolaoAdminController extends Controller{

    public function __construct(){
        return $this->middleware('auth:web-admin');
    }

    public function index(Request $request){
        $bolaos = Bolao::all();
        foreach ($bolaos as $bolao) {
            $bolao->data_abertura = MinhaClasse::data_mysql_to_datahora_formatada($bolao->data_abertura);
            $bolao->data_fechamento = MinhaClasse::data_mysql_to_datahora_formatada($bolao->data_fechamento);
        }
        return view('admin.bolaos.listabolaos', compact('bolaos'));
    }    

    public function novo(Request $request){
    	return view('admin.bolaos.novobolao');
    }

    public function create(Request $request){
    	$bolao = new Bolao();
    	$bolao->nome = $request->nome;
    	$bolao->quant_eventos = $request->quant_eventos;
    	$bolao->valor_aposta = $request->valor_aposta;
    	$bolao->data_abertura = $request->data_abertura;
    	$bolao->data_fechamento = $request->data_fechamento;
    	$bolao->status_id = $request->status_id;
    	$bolao->save();
    	return redirect()->route('admin_showbolao', ['id' => $bolao->id]);
    }

    public function editar(Request $request, $id){
    	$bolao = Bolao::find($id);
    	return view('admin.bolaos.editarbolao', compact('bolao'));
    }

    public function atualizar(Request $request, $id){
    	$bolao = Bolao::find($id);
        if(isset($bolao)){
            $bolao->nome = $request->nome;
            $bolao->quant_eventos = $request->quant_eventos;
            $bolao->valor_aposta = $request->valor_aposta;
            $bolao->data_abertura = $request->data_abertura;
            $bolao->data_fechamento = $request->data_fechamento;
            $bolao->status_id = $request->status_id;
            $bolao->save();
            return back();
        }

        return response('', 404);
        	
    }

    public function addEventos(Request $request, $id){
        $request->validate([
            'evento_id' => 'required|integer'
        ]);

        $bolao = Bolao::find($id);
        if(isset($bolao)){
            $eventoBolao = new EventoBolao();
            $eventoBolao->bolao_id = $bolao->id;
            $eventoBolao->evento_id = $request->evento_id;
            $addOk = $eventoBolao->save();            
            $eventoBolao->evento->time1;
            $eventoBolao->evento->time2;
            return response()->json($eventoBolao);
        }

        return response('', 404);
    }

    public function removeEventos(Request $request){
        $request->validate([
            'evento_id' => 'required|integer'
        ]);

        $eventoBolao = EventoBolao::find($request->evento_id);
        if( isset($eventoBolao) ){
            $eventoBolao->delete();
            return response()->json([
                'sucesso' => true,
                'msg' => 'Evento removido com sucesso!'
            ]);
        }else{
            return response()->json([
                'sucesso' => false,
                'msg' => 'Evento não encontrado!'
            ]);
        }
        
    }

    public function show(Request $request, $id){
    	$bolao = Bolao::find($id);
        if( !isset($bolao) ){
            return response('', 404);
        }
    	$bolao->eventos;
    	$ligas = Liga::where('is_top_list', '>=', '1')
    		->orderBy('is_top_list', 'desc')
            ->orderBy('nome')
    		->get();

        // return $bolao;
    	return view('admin.bolaos.viewbolao', compact('bolao', 'ligas'));
    }

    public function listar(Request $request){
    	$bolaos = Bolao::all();
    	return $bolaos;
    }

}
