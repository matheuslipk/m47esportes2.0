<?php

namespace App\Http\Controllers\Api;

date_default_timezone_set('America/Fortaleza');

use App\Http\Controllers\Controller;

class ConverterApi2 extends Controller{

   public static function converterOdds2($odds){
      $oddsConvertidas = new \stdClass;
      $oddsConvertidas->evento_id = $odds->event_id;
      $oddsConvertidas->FI_365 = $odds->FI;
      if(isset($odds->main->JsonData->F[0]->FD)){
         $oddsConvertidas->times = explode(" v ", $odds->main->JsonData->F[0]->FD);
      }else{
         return $oddsConvertidas;
      }
      


      if( isset( $odds->goals->JsonData->F[0]->ML ) ){
         $catGoals365 = $odds->goals->JsonData->F[0]->ML;

         foreach ($catGoals365 as $cat) {
            if($cat->MD == "Team Total Goals"){
               $timeTotalGols = $cat;      

               $oddsConvertidas->cat_palpites[]= ConverterApi2::timeTotalGolsCasa($timeTotalGols, $oddsConvertidas->times);
               $oddsConvertidas->cat_palpites[]= ConverterApi2::timeTotalGolsFora($timeTotalGols, $oddsConvertidas->times);
            }
         }

               
      }

      return $oddsConvertidas;
   }

   private static function timeTotalGolsCasa($timeTotalGols, $times){
      $obj = new \stdClass;
      $obj->categoria_id = 3;
      $obj->nome = "Total de Gols Casa";
      $obj->tempo = "90 min";

      foreach ($timeTotalGols->PL as $opc){
         $nomeTime = str_replace(" Under", "", $opc->PD);
         $nomeTime = str_replace(" Over", "", $nomeTime);
         $under_over = str_replace($nomeTime." ", "", $opc->PD);
         $odds = explode("#", $opc->BS)[1];
         $odds = explode("=", $odds)[1];
         $odds = explode("/", $odds);
         $odds = number_format(($odds[0]/$odds[1])+1, 2);         

         if($nomeTime == $times[0]){
            if($opc->HD == '0.5'){
               if($under_over == 'Under'){
                  $nome = 'Casa: -0.5';
                  $tipoPalpiteID = 17;
               }else{
                  $tipoPalpiteID = 7;
                  $nome = 'Casa: +0.5';
               }
            }

            elseif($opc->HD == '1.5'){
               if($under_over == 'Under'){
                  $nome = 'Casa: -1.5';
                  $tipoPalpiteID = 18;
               }else{
                  $tipoPalpiteID = 8;
                  $nome = 'Casa: +1.5';
               }
            }

            elseif($opc->HD == '2.5'){
               if($under_over == 'Under'){
                  $nome = 'Casa: -2.5';
                  $tipoPalpiteID = 19;
               }else{
                  $nome = 'Casa: +2.5';
                  $tipoPalpiteID = 9;
               }
            }

            elseif($opc->HD == '3.5'){
               if($under_over == 'Under'){
                  $nome = 'Casa: -3.5';
                  $tipoPalpiteID = 20;
               }else{
                  $nome = 'Casa: +3.5';
                  $tipoPalpiteID = 10;
               }
            }

            elseif($opc->HD == '4.5'){
               if($under_over == 'Under'){
                  $nome = 'Casa: -4.5';
                  $tipoPalpiteID = 21;
               }else{
                  $nome = 'Casa: +4.5';
                  $tipoPalpiteID = 11;
               }
            }

            elseif($opc->HD == '5.5'){
               if($under_over == 'Under'){
                  $nome = 'Casa: -5.5';
                  $tipoPalpiteID = 22;
               }else{
                  $nome = 'Casa: +5.5';
                  $tipoPalpiteID = 12;
               }
            }

            $temp = [
               // 'handicap' => $opc->HD,
               // 'nome_time' => $nomeTime,
               // 'under_over' => $under_over,       
               'tipo_palpite_id' => $tipoPalpiteID,        
               'nome' => $nome,
               'taxa' => $odds,               
            ];
            unset($tipoPalpiteID);

            $odd[]=$temp;
         }

      }
      $obj->odds = $odd;

      return $obj;
   }

   private static function timeTotalGolsFora($timeTotalGols, $times){
      $obj = new \stdClass;
      $obj->categoria_id = 4;
      $obj->nome = "Total de Gols Fora";
      $obj->tempo = "90 min";

      foreach ($timeTotalGols->PL as $opc){
         $nomeTime = str_replace(" Under", "", $opc->PD);
         $nomeTime = str_replace(" Over", "", $nomeTime);
         $under_over = str_replace($nomeTime." ", "", $opc->PD);
         $odds = explode("#", $opc->BS)[1];
         $odds = explode("=", $odds)[1];
         $odds = explode("/", $odds);
         $odds = number_format(($odds[0]/$odds[1])+1, 2);         

         if($nomeTime == $times[1]){
            if($opc->HD == '0.5'){
               if($under_over == 'Under'){
                  $nome = 'Fora: -0.5';
                  $tipoPalpiteID = 37;
               }else{
                  $nome = 'Fora: +0.5';
                  $tipoPalpiteID = 27;
               }
            }

            elseif($opc->HD == '1.5'){
               if($under_over == 'Under'){
                  $nome = 'Fora: -1.5';
                  $tipoPalpiteID = 38;
               }else{
                  $nome = 'Fora: +1.5';
                  $tipoPalpiteID = 28;
               }
            }

            elseif($opc->HD == '2.5'){
               if($under_over == 'Under'){
                  $nome = 'Fora: -2.5';
                  $tipoPalpiteID = 39;
               }else{
                  $nome = 'Fora: +2.5';
                  $tipoPalpiteID = 29;
               }
            }

            elseif($opc->HD == '3.5'){
               if($under_over == 'Under'){
                  $nome = 'Fora: -3.5';
                  $tipoPalpiteID = 40;
               }else{
                  $nome = 'Fora: +3.5';
                  $tipoPalpiteID = 30;
               }
            }

            elseif($opc->HD == '4.5'){
               if($under_over == 'Under'){
                  $nome = 'Fora: -4.5';
                  $tipoPalpiteID = 41;
               }else{
                  $nome = 'Fora: +4.5';
                  $tipoPalpiteID = 31;
               }
            }

            elseif($opc->HD == '5.5'){
               if($under_over == 'Under'){
                  $nome = 'Fora: -5.5';
                  $tipoPalpiteID = 42;
               }else{
                  $nome = 'Fora: +5.5';
                  $tipoPalpiteID = 32;
               }
            }

            $temp = [
               // 'handicap' => $opc->HD,
               // 'nome_time' => $nomeTime,
               // 'under_over' => $under_over,             
               'tipo_palpite_id' => $tipoPalpiteID,  
               'nome' => $nome,
               'taxa' => $odds,
            ];
            unset($tipoPalpiteID);

            $odd[]=$temp;
         }


      }
      $obj->odds = $odd;

      return $obj;
   }


}