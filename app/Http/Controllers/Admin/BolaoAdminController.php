<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Bolao;
use App\Liga;
use App\EventoBolao;
use App\BolaoEventoBolao;
use App\Http\Controllers\Api\MinhaClasse;

class BolaoAdminController extends Controller{

    public function __construct(){
        return $this->middleware('auth:web-admin');
    }

    public function index(Request $request){
        $bolaos = Bolao::all()->sortByDesc('id');



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
        $bolao->comissao_agente = $request->comissao_agente;
        $bolao->comissao_casa = $request->comissao_casa;
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
            $bolao->comissao_agente = $request->comissao_agente;
            $bolao->comissao_casa = $request->comissao_casa;
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
            $eventoBolao = new BolaoEventoBolao();
            $eventoBolao->bolao_id = $bolao->id;
            $eventoBolao->evento_bolao_id = $request->evento_id;
            $eventoBolao->save();          
            return back();
        }

        return response('', 404);
    }

    public function removeEventos(Request $request){
        $request->validate([
            'evento_id' => 'required|integer',
            'bolao_id' => 'required|integer',
        ]);

        $eventoBolao = BolaoEventoBolao::where([
            [ 'evento_bolao_id', $request->evento_id ],
            [ 'bolao_id', $request->bolao_id ],
        ])->get();

        if( isset($eventoBolao) ){
            BolaoEventoBolao::where([
                [ 'evento_bolao_id', $request->evento_id ],
                [ 'bolao_id', $request->bolao_id ],
            ])->delete();

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

        $bolao->eventosBolao;

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
