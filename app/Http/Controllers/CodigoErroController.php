<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CodigoErro;

class CodigoErroController extends Controller
{
    public function index(Request $request, $id)
    {
        $erro = CodigoErro::find($id);
        if(isset($erro)){
            return view('erros', compact('erro'));
        }else{
            return response("",404);
        }
    }

}
