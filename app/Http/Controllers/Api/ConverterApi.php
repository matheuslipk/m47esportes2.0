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

      if(isset($odds->main->sp->correct_score)){
         $placarExato = $odds->main->sp->correct_score;
         $oddsConvertidas->cat_palpites[] = ConverterApi::placarExato($placarExato);
      }

      if(isset($odds->main->sp->winning_margin)){
         $margemVitoria = $odds->main->sp->winning_margin;
         $oddsConvertidas->cat_palpites[] = ConverterApi::margemVitoria($margemVitoria);
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

   private static function placarExato($placarExato){
      $obj = new \stdClass;
      $obj->categoria_id = 8;
      $obj->nome = "Placar Exato";
      $obj->tempo = "90 min";


      foreach ($placarExato as $opc){
         
         if($opc->header=="Home"){
            switch ($opc->opp){
               case("1-0"):
                  $temp =[
                     'tipo_palpite_id'=>510,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("2-0"):
                  $temp =[
                     'tipo_palpite_id'=>520,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("2-1"):
                  $temp =[
                     'tipo_palpite_id'=>521,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("3-0"):
                  $temp =[
                     'tipo_palpite_id'=>530,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("3-1"):
                  $temp =[
                     'tipo_palpite_id'=>531,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("3-2"):
                  $temp =[
                     'tipo_palpite_id'=>532,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("4-0"):
                  $temp =[
                     'tipo_palpite_id'=>540,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("4-1"):
                  $temp =[
                     'tipo_palpite_id'=>541,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("4-2"):
                  $temp =[
                     'tipo_palpite_id'=>542,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("4-3"):
                  $temp =[
                     'tipo_palpite_id'=>543,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("5-0"):
                  $temp =[
                     'tipo_palpite_id'=>550,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("5-1"):
                  $temp =[
                     'tipo_palpite_id'=>551,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("5-2"):
                  $temp =[
                     'tipo_palpite_id'=>552,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
            }            
         }
         if($opc->header=="X"){
            switch ($opc->opp){
               case("0-0"):
                  $temp =[
                     'tipo_palpite_id'=>500,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("1-1"):
                  $temp =[
                     'tipo_palpite_id'=>511,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("2-2"):
                  $temp =[
                     'tipo_palpite_id'=>522,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("3-3"):
                  $temp =[
                     'tipo_palpite_id'=>533,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("4-4"):
                  $temp =[
                     'tipo_palpite_id'=>544,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
            }            
         }
         if($opc->header=="Away"){
            switch ($opc->opp){
               case("1-0"):
                  $temp =[
                     'tipo_palpite_id'=>510,
                     'nome'=> '0-1',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("2-0"):
                  $temp =[
                     'tipo_palpite_id'=>502,
                     'nome'=> '0-2',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("2-1"):
                  $temp =[
                     'tipo_palpite_id'=>512,
                     'nome'=> '1-2',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("3-0"):
                  $temp =[
                     'tipo_palpite_id'=>503,
                     'nome'=> '0-3',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("3-1"):
                  $temp =[
                     'tipo_palpite_id'=>513,
                     'nome'=> '1-3',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("3-2"):
                  $temp =[
                     'tipo_palpite_id'=>523,
                     'nome'=> '2-3',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("4-0"):
                  $temp =[
                     'tipo_palpite_id'=>504,
                     'nome'=> '0-4',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("4-1"):
                  $temp =[
                     'tipo_palpite_id'=>514,
                     'nome'=> '1-4',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("4-2"):
                  $temp =[
                     'tipo_palpite_id'=>524,
                     'nome'=> '2-4',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("4-3"):
                  $temp =[
                     'tipo_palpite_id'=>534,
                     'nome'=> '3-4',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("5-0"):
                  $temp =[
                     'tipo_palpite_id'=>505,
                     'nome'=> '0-5',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("5-1"):
                  $temp =[
                     'tipo_palpite_id'=>515,
                     'nome'=> '1-5',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("5-2"):
                  $temp =[
                     'tipo_palpite_id'=>525,
                     'nome'=> '2-5',
                     'taxa'=> $opc->odds,
                  ];
                  break;
            }            
         }
         $odd[]=$temp;
      }

      $obj->odds = $odd;
      return $obj;
   }

   private static function margemVitoria($odds){
      $obj = new \stdClass;
      $obj->categoria_id = 17;
      $obj->nome = "Margem de vitória";
      $obj->tempo = "90 min";

      foreach ($odds as $opc){
         switch ($opc->header){
            case("Home"):
               if($opc->goals==" 1"){
                  $temp =[
                     'tipo_palpite_id'=>80,
                     'nome'=> 'Casa por 1 gol',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               }
               if($opc->goals==" 2"){
                  $temp =[
                     'tipo_palpite_id'=>81,
                     'nome'=> 'Casa por 2 gos',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               }
               if($opc->goals==" 3"){
                  $temp =[
                     'tipo_palpite_id'=>82,
                     'nome'=> 'Casa por 3 gols',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               }
               if($opc->goals==" 4+"){
                  $temp =[
                     'tipo_palpite_id'=>83,
                     'nome'=> 'Casa por 4 ou + gols',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               }
               if($opc->goals=="Score Draw"){
                  $temp =[
                     'tipo_palpite_id'=>88,
                     'nome'=> 'Empate com gols',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               }
               if($opc->goals=="No Goal"){
                  $temp =[
                     'tipo_palpite_id'=>89,
                     'nome'=> 'Empate sem gols',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               }
               
            case("Away"):
               if($opc->goals==" 1"){
                  $temp =[
                     'tipo_palpite_id'=>84,
                     'nome'=> 'Fora por 1 gol',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               }
               if($opc->goals==" 2"){
                  $temp =[
                     'tipo_palpite_id'=>85,
                     'nome'=> 'Fora por 2 gols',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               }   
               if($opc->goals==" 3"){
                  $temp =[
                     'tipo_palpite_id'=>86,
                     'nome'=> 'Fora por 3 gols',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               }
               if($opc->goals==" 4+"){
                  $temp =[
                     'tipo_palpite_id'=>87,
                     'nome'=> 'Fora por 4 ou + gols',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               }
         }
         $odd[]=$temp;
      }

      $obj->odds = $odd;
      return $obj;
   }
}