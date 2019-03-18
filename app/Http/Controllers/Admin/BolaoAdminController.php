<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Bolao;
use App\Liga;
use App\EventoBolao;

class BolaoAdminController extends Controller{

    public function __construct(){
        return $this->middleware('auth:web-admin');
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
            return back();
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
