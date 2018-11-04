<?php

namespace App\Http\Controllers\Api;

date_default_timezone_set('America/Fortaleza');

use App\Http\Controllers\Controller;

class MinhaClasse extends Controller{
	


    public static function get_token(){
    	return "5054-tkgWmwK03HzSx6";
    }
    
    public static function fazer_requisicao($url, $variaveis, $metodo){
        $conteudo = http_build_query($variaveis);
//        return $conteudo;
        $opts = array(
           'http'=>array(
              'method'=>$metodo,
              'header'=>"Connection: close\r\n".
              "Content-type: application/x-www-form-urlencoded\r\n".
              "Content-Length: ".strlen($conteudo)."\r\n",
              'content' => $conteudo
           )
        );
        $contexto = stream_context_create($opts);
        return file_get_contents($url, NULL, $contexto);   
    }

   public static function timestamp_to_data_mysql($timestamp){
       return date("Y-m-d H:i:s", $timestamp);
    }
 
}
