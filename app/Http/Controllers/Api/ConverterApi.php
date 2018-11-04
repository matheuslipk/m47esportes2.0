<?php

namespace App\Http\Controllers\Api;

date_default_timezone_set('America/Fortaleza');

use App\Http\Controllers\Controller;

class ConverterApi extends Controller{

   public static function converterOdds($odds){
      $oddsConvertidas = new \stdClass;

      if(isset($odds->main->sp->full_time_result)){         
         $tempoCompleto = $odds->main->sp->full_time_result;         
         $oddsConvertidas->vencedor_encontro = ConverterApi::vencedorEncontro($tempoCompleto);
      }
      if(isset($odds->main->sp->double_chance)){
         $duplaChance = $odds->main->sp->double_chance;
         $oddsConvertidas->dupla_chance = ConverterApi::duplaChance($duplaChance);
      }      
      if(isset($odds->main->sp->both_teams_to_score)){
         $ambosMarcam = $odds->main->sp->both_teams_to_score;
         $oddsConvertidas->ambos_marcam = ConverterApi::ambosMarcam($ambosMarcam);
      }
      // if(isset($odds->goals->sp->alternative_total_goals)){
      //    $oddsConvertidas->cat_palpite[5] = ConverterApi::quantGols($odds);
      // }

      if(isset($odds->main->sp->result_both_teams_to_score)){
         $resultadoFinalEAmbas = $odds->main->sp->result_both_teams_to_score;
         $oddsConvertidas->result_final_e_ambas = ConverterApi::resultFinalEAmbas($resultadoFinalEAmbas);
      }

      return $oddsConvertidas;

   }


   private static function vencedorEncontro($fullTime){
      $obj = new \stdClass;
      $obj->categoria_id = 1;
      $obj->nome = "Vencedor Encontro";
      $obj->tempo = "90 min";

      foreach ($fullTime as $opc){         
         if($opc->opp=="1"){
            $temp =[
               'tipo_palpite_id'=>1,
               'nome'=>'Casa',
               'taxa'=>$opc->odds,
            ];
         }
         if($opc->opp=="X"){
            $temp =[
               'tipo_palpite_id'=>2,
               'nome'=>'Empate',
               'taxa'=>$opc->odds,
            ];
         }
         if($opc->opp=="2"){
            $temp =[
               'tipo_palpite_id'=>3,
               'nome'=>'Fora',
               'taxa'=>$opc->odds,
            ];            
         }
         $odd[]=$temp;

      }
      $obj->odds = $odd;
      return $obj;
   }

   private static function duplaChance($doubleChance){
      $obj = new \stdClass;
      $obj->categoria_id = 2;
      $obj->nome = "Dupla Chance";
      $obj->tempo = "90 min";

      foreach ($doubleChance as $opc){
         if($opc->opp=="12"){
            $temp = [
               'tipo_palpite_id'=>4,
               'nome'=>'Casa ou Fora',
               'taxa'=>$opc->odds,
            ];
         }
         if($opc->opp=="1X"){
            $temp = [
               'tipo_palpite_id'=>5,
               'nome'=>'Casa ou Empate',
               'taxa'=>$opc->odds,
            ];
         }
         if($opc->opp=="X2"){
            $temp = [
               'tipo_palpite_id'=>6,
               'nome'=>'Empate ou Fora',
               'taxa'=>$opc->odds,
            ];
         }
         $odd[]=$temp;
      }
      $obj->odds = $odd;
      return $obj;
   }

   private static function ambosMarcam($ambos){
      $obj = new \stdClass;
      $obj->categoria_id = 2;
      $obj->nome = "Ambos Marcam";
      $obj->tempo = "90 min";

      foreach ($ambos as $opc){
         if($opc->opp=="Yes"){
            $temp = [
               'tipo_palpite_id'=>69,
               'nome'=>'Sim',
               'taxa'=>$opc->odds,
            ];
         }
         if($opc->opp=="No"){
            $temp = [
               'tipo_palpite_id'=>70,
               'nome'=>'Não',
               'taxa'=>$opc->odds,
            ];
         }
         $odd[]=$temp;
      }

      $obj->odds = $odd;
      return $obj;
   }

   private static function quantGols($odds){
      if(isset($odds->goals->sp->alternative_total_goals)){
         $golsAlternativos = $odds->goals->sp->alternative_total_goals;
      }
      
      if(isset($odds->goals->sp->goals_over_under)){
         $gols = $odds->goals->sp->goals_over_under;
      }
      
      foreach ($golsAlternativos as $opc){
         switch ($opc->header){
            case("Over "):
               if($opc->goals=="1.5"){
                  $retorno[48] = $opc->odds;
                  break;
               }
               if($opc->goals=="2.5"){
                  $retorno[49] = $opc->odds;
                  break;
               }
               if($opc->goals=="3.5"){
                  $retorno[50] = $opc->odds;
                  break;
               }
               if($opc->goals=="4.5"){
                  $retorno[51] = $opc->odds;
                  break;
               }
               if($opc->goals=="5.5"){
                  $retorno[52] = $opc->odds;
                  break;
               }
            case("Under"):
               if($opc->goals=="1.5"){
                  $retorno[58] = $opc->odds;
                  break;
               }
               if($opc->goals=="2.5"){
                  $retorno[59] = $opc->odds;
                  break;
               }   
               if($opc->goals=="3.5"){
                  $retorno[60] = $opc->odds;
                  break;
               }
               if($opc->goals=="4.5"){
                  $retorno[61] = $opc->odds;
                  break;
               }
               if($opc->goals=="5.5"){
                  $retorno[62] = $opc->odds;
                  break;
               }
         }
      }
      
      foreach ($gols as $opc){
         switch ($opc->header){
            case("Over"):
               if($opc->goals=="1.5"){
                  $retorno[48] = $opc->odds;
                  break;
               }
               if($opc->goals=="2.5"){
                  $retorno[49] = $opc->odds;
                  break;
               }
               if($opc->goals=="3.5"){
                  $retorno[50] = $opc->odds;
                  break;
               }
               if($opc->goals=="4.5"){
                  $retorno[51] = $opc->odds;
                  break;
               }
               
            case("Under"):
               if($opc->goals=="1.5"){
                  $retorno[58] = $opc->odds;
                  break;
               }
               if($opc->goals=="2.5"){
                  $retorno[59] = $opc->odds;
                  break;
               }   
               if($opc->goals=="3.5"){
                  $retorno[60] = $opc->odds;
                  break;
               }
               if($opc->goals=="4.5"){
                  $retorno[61] = $opc->odds;
                  break;
               }
         }
      }
      
      return $retorno;
   }

   private static function resultFinalEAmbas($ambos){

      $obj = new \stdClass;
      $obj->categoria_id = 14;
      $obj->nome = "Resultado final & Ambos Marcam";
      $obj->tempo = "90 min";

      $odd[]=[
         'tipo_palpite_id'=>824,
         'nome'=>'Casa & Sim',
         'taxa'=>$ambos[0]->odds
      ];
      $odd[]=[
         'tipo_palpite_id'=>827,
         'nome'=>'Casa & Não',
         'taxa'=>$ambos[1]->odds
      ];
      $odd[]=[
         'tipo_palpite_id'=>825,
         'nome'=>'Empate & Sim',
         'taxa'=>$ambos[4]->odds
      ];
      $odd[]=[
         'tipo_palpite_id'=>828,
         'nome'=>'Empate & Não',
         'taxa'=>$ambos[5]->odds
      ];
      $odd[]=[
         'tipo_palpite_id'=>826,
         'nome'=>'Fora & Sim',
         'taxa'=>$ambos[2]->odds
      ];
      $odd[]=[
         'tipo_palpite_id'=>829,
         'nome'=>'Fora & Não',
         'taxa'=>$ambos[3]->odds
      ];

      $obj->odds = $odd;
      return $obj;      
   }
}