<?php

namespace App\Http\Controllers\Api;

date_default_timezone_set('America/Fortaleza');

use App\Http\Controllers\Controller;
use App\Odd;

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
 
   public static function timestamp_to_datahora_formatada($timestamp){
      return date("d/m/Y H:i", $timestamp);
   }

   public static function date_mysql_to_timestamp($str){
      return strtotime($str);
   }

   public static function data_mysql_to_datahora_formatada($dataMysql){
      $timestamp = MinhaClasse::date_mysql_to_timestamp($dataMysql);
      return MinhaClasse::timestamp_to_datahora_formatada($timestamp);
   }

   public static function atualizarOdds($evento){
      $url = "https://api.betsapi.com/v1/bet365/start_sp";
      $metodo = "GET";
      $variaveis["token"] = MinhaClasse::get_token();
      $variaveis["event_id"] = $evento;
      
      $odds = MinhaClasse::fazer_requisicao($url, $variaveis, $metodo); 
      $objetoOdds = json_decode($odds);

      if(isset($objetoOdds->results[0])){
         $oddsConvertidas = Odd::inserir_odds($objetoOdds->results[0], $variaveis['event_id']);
         return json_encode( (array) $oddsConvertidas);
      }
      
      return 'Nenhum evento encontrado';
   }
}
