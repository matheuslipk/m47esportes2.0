<?php

namespace App\Http\Controllers\Agente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Cliente;

class ClienteAgenteController extends Controller
{
    public function __construct(){
        return $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){

        $request->validate([
            'page' => 'integer'
        ]);

        $quantPorPagina = 5;
        $pagina = $request->page;
        
        $agente = Auth::user();
        $clientes = DB::table('clientes')
            ->where('agente_id', $agente->id)
            ->orderBy('nome', 'asc')
            ->limit( $quantPorPagina )
            ->offset( $quantPorPagina*$pagina )
            ->get();

        $quantClientes = DB::table('clientes')
            ->where('agente_id', $agente->id)->count();

        $quantPaginas = round($quantClientes/$quantPorPagina) ;

        return view('agente.clientes.lista_clientes', compact('clientes', 'quantPaginas', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('agente.clientes.novo_cliente');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $request->validate([
            'nome' => 'required',
            'telefone' => 'required',
        ]);

        $nome = ucwords( mb_strtolower( trim($request->nome) ) );
        $cliente = Cliente::where([
            ['agente_id', $request->user()->id],
            ['nome', $nome]
        ])->first();

        if( isset($cliente) )return response("Ja existe um cliente na sua lista com esse nome", 422);

        $cliente = new Cliente();
        $cliente->agente_id = $request->user()->id;
        $cliente->nome =   $nome;
        $cliente->telefone = $request->telefone;
        $cliente->save();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id){
        $agente = $request->user();
        $cliente = Cliente::find($id);
        if( isset($cliente) && ($cliente->agente_id === $agente->id) ){
            return view('agente.clientes.editar_cliente', compact('cliente'));
        }

        return $id;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){

        $request->validate([
            'id' => 'integer|required',
            'nome' => 'required',
            'telefone' => 'required',
        ]);

        $agente = $request->user();
        $cliente = Cliente::find($id);

        $nome = ucwords( mb_strtolower( trim($request->nome) ) );

        if( isset($cliente) && ($cliente->agente_id === $agente->id) ){
            $cliente->nome = $nome;
            $cliente->telefone = $request->telefone;
            $cliente->save();
            return response([
                'sucesso' => true
            ]);
        }
        return response("erro", 422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
