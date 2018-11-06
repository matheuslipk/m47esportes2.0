<?php

namespace App\Http\Controllers\Api;

date_default_timezone_set('America/Fortaleza');

use App\Http\Controllers\Controller;

class ConverterApi extends Controller{

   public static function converterOdds($odds){
      $oddsConvertidas = new \stdClass;
      $oddsConvertidas->evento_id = $odds->event_id;
      $oddsConvertidas->FI_365 = $odds->FI;

      if(isset($odds->main->sp->full_time_result)){         
         $tempoCompleto = $odds->main->sp->full_time_result;      
         $oddsConvertidas->cat_palpites[]= ConverterApi::vencedorEncontro($tempoCompleto);
      }
      if(isset($odds->main->sp->double_chance)){
         $duplaChance = $odds->main->sp->double_chance;
         $oddsConvertidas->cat_palpites[] = ConverterApi::duplaChance($duplaChance);
      }      
      if(isset($odds->main->sp->both_teams_to_score)){
         $ambosMarcam = $odds->main->sp->both_teams_to_score;
         $oddsConvertidas->cat_palpites[] = ConverterApi::ambosMarcam($ambosMarcam);
      }
      if(isset($odds->goals->sp->alternative_total_goals)){
         $oddsConvertidas->cat_palpites[] = ConverterApi::quantGols($odds);
      }

      if(isset($odds->main->sp->result_both_teams_to_score)){
         $resultadoFinalEAmbas = $odds->main->sp->result_both_teams_to_score;
         $oddsConvertidas->cat_palpites[] = ConverterApi::resultFinalEAmbas($resultadoFinalEAmbas);
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
      $obj->categoria_id = 6;
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
      $obj = new \stdClass;
      $obj->categoria_id = 5;
      $obj->nome = "Total de Gols";
      $obj->tempo = "90 min";

      $temp=[];
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
                  $temp = [
                     'tipo_palpite_id'=>48,
                     'nome'=>'+1.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
               if($opc->goals=="2.5"){
                  $temp = [
                     'tipo_palpite_id'=>49,
                     'nome'=>'+2.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
               if($opc->goals=="3.5"){
                  $temp = [
                     'tipo_palpite_id'=>50,
                     'nome'=>'+3.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
               if($opc->goals=="4.5"){
                  $temp = [
                     'tipo_palpite_id'=>51,
                     'nome'=>'+4.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
               if($opc->goals=="5.5"){
                  $temp = [
                     'tipo_palpite_id'=>52,
                     'nome'=>'+5.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
            case("Under"):
               if($opc->goals=="1.5"){
                  $temp = [
                     'tipo_palpite_id'=>58,
                     'nome'=>'-1.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
               if($opc->goals=="2.5"){
                  $temp = [
                     'tipo_palpite_id'=>59,
                     'nome'=>'-2.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }   
               if($opc->goals=="3.5"){
                  $temp = [
                     'tipo_palpite_id'=>60,
                     'nome'=>'-3.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
               if($opc->goals=="4.5"){
                  $temp = [
                     'tipo_palpite_id'=>61,
                     'nome'=>'-4.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
               if($opc->goals=="5.5"){
                  $temp = [
                     'tipo_palpite_id'=>62,
                     'nome'=>'-5.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
         }
         
      }
      
      foreach ($gols as $opc){
         switch ($opc->header){
            case("Over"):
               if($opc->goals=="1.5"){
                  $temp = [
                     'tipo_palpite_id'=>48,
                     'nome'=>'+1.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
               if($opc->goals=="2.5"){
                  $temp = [
                     'tipo_palpite_id'=>49,
                     'nome'=>'+2.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
               if($opc->goals=="3.5"){
                  $temp = [
                     'tipo_palpite_id'=>50,
                     'nome'=>'+3.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
               if($opc->goals=="4.5"){
                  $temp = [
                     'tipo_palpite_id'=>51,
                     'nome'=>'+4.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }             
            case("Under"):
               if($opc->goals=="1.5"){
                  $temp = [
                     'tipo_palpite_id'=>58,
                     'nome'=>'-1.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
               if($opc->goals=="2.5"){
                  $temp = [
                     'tipo_palpite_id'=>59,
                     'nome'=>'-2.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }   
               if($opc->goals=="3.5"){
                  $temp = [
                     'tipo_palpite_id'=>60,
                     'nome'=>'-3.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
               if($opc->goals=="4.5"){
                  $temp = [
                     'tipo_palpite_id'=>61,
                     'nome'=>'-4.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
         }
      }

      $obj->odds = $odd;
      return $obj;
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