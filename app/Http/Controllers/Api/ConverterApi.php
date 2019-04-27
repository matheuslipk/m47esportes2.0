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

      if(isset($odds->goals->sp->exact_total_goals)){
         $golsExatos = $odds->goals->sp->exact_total_goals;
         $oddsConvertidas->cat_palpites[] = ConverterApi::golsExatos($golsExatos);
      }

      if(isset($odds->goals->sp->total_goals_both_teams_to_score)){
         $golsComAmbas = $odds->goals->sp->total_goals_both_teams_to_score;
         $oddsConvertidas->cat_palpites[] = ConverterApi::golsComAmbas($golsComAmbas);
      }

      if(isset($odds->goals->sp->goals_odd_even)){
         $golsParImpar = $odds->goals->sp->goals_odd_even;
         $oddsConvertidas->cat_palpites[] = ConverterApi::golsParImpar($golsParImpar);
      }

      if(isset($odds->goals->sp->result_total_goals)){
         $resultadoEGols = $odds->goals->sp->result_total_goals;
         $oddsConvertidas->cat_palpites[] = ConverterApi::resultadoEGols($resultadoEGols);
      }

      //Primeiro tempo
      if(isset($odds->half->sp->half_time_result)){
         $vencedor1T = $odds->half->sp->half_time_result;
         $oddsConvertidas->cat_palpites[] = ConverterApi::vencedor1T($vencedor1T);
      }
      if(isset($odds->half->sp->half_time_double_chance)){
         $duplaChance1T = $odds->half->sp->half_time_double_chance;
         $oddsConvertidas->cat_palpites[] = ConverterApi::duplaChance1T($duplaChance1T);
      }
      if(isset($odds->half->sp->first_half_goals)){
         $oddsConvertidas->cat_palpites[] = ConverterApi::quantGols1T($odds);
      }
      if(isset($odds->half->sp->both_teams_to_score_in_1st_half)){
         $AmbosMarcam1T = $odds->half->sp->both_teams_to_score_in_1st_half;
         $oddsConvertidas->cat_palpites[] = ConverterApi::ambosMarcam1T($AmbosMarcam1T);
      }
      if(isset($odds->half->sp->half_time_correct_score)){
         $placarExato1T = $odds->half->sp->half_time_correct_score;
         $oddsConvertidas->cat_palpites[] = ConverterApi::placarExato1T($placarExato1T);
      }
      
      //Segundo Tempo
      $t = "2nd_half_result";
      if(isset($odds->half->sp->$t)){
         $vencedor2T = $odds->half->sp->$t;
         $oddsConvertidas->cat_palpites[] = ConverterApi::vencedor2T($vencedor2T);
      }
      if(isset($odds->half->sp->both_teams_to_score_in_2nd_half)){
         $AmbosMarcam2T = $odds->half->sp->both_teams_to_score_in_2nd_half;
         $oddsConvertidas->cat_palpites[] = ConverterApi::ambosMarcam2T($AmbosMarcam2T);
      }
      $t = "2nd_half_goals";
      if(isset($odds->half->sp->$t)){
         $tempGols2Tempo = $odds->half->sp->$t;
         $oddsConvertidas->cat_palpites[] = ConverterApi::quantGols2T($tempGols2Tempo);
      }

      //Tempo Misto
      if(isset($odds->goals->sp->home_team_highest_scoring_half)){
         $casaMaisGols = $odds->goals->sp->home_team_highest_scoring_half;
         $oddsConvertidas->cat_palpites[] = ConverterApi::casaMaisGols($casaMaisGols);
      }

      if(isset($odds->goals->sp->away_team_highest_scoring_half)){
         $foraMaisGols = $odds->goals->sp->away_team_highest_scoring_half;
         $oddsConvertidas->cat_palpites[] = ConverterApi::foraMaisGols($foraMaisGols);
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
      
      if(isset($gols)){
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
                     'tipo_palpite_id'=>501,
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
         if( isset($temp) ){
            $odd[]=$temp;
         }
         
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

   private static function golsExatos($gols){
      $obj = new \stdClass;
      $obj->categoria_id = 15;
      $obj->nome = "Gols Exatos na partida";
      $obj->tempo = "90 min";

      foreach ($gols as $gol){

         if($gol->opp=="0 Goals"){
            $temp = [
               'tipo_palpite_id'=>150,
               'nome'=> '0 gols',
               'taxa'=> $gol->odds,
            ];
         }
         if($gol->opp=="1 Goal"){
            $temp = [
               'tipo_palpite_id'=>151,
               'nome'=> '1 gol',
               'taxa'=> $gol->odds,
            ];
         }
         if($gol->opp=="2 Goals"){
            $temp = [
               'tipo_palpite_id'=>152,
               'nome'=> '2 gols',
               'taxa'=> $gol->odds,
            ];
         }
         if($gol->opp=="3 Goals"){
            $temp = [
               'tipo_palpite_id'=>153,
               'nome'=> '3 gols',
               'taxa'=> $gol->odds,
            ];
         }
         if($gol->opp=="4 Goals"){
            $temp = [
               'tipo_palpite_id'=>154,
               'nome'=> '4 gols',
               'taxa'=> $gol->odds,
            ];
         }
         if($gol->opp=="5 Goals"){
            $temp = [
               'tipo_palpite_id'=>155,
               'nome'=> '5 gols',
               'taxa'=> $gol->odds,
            ];
         }
         if($gol->opp=="6 Goals"){
            $temp = [
               'tipo_palpite_id'=>156,
               'nome'=> '6 gols',
               'taxa'=> $gol->odds,
            ];
         }
         if($gol->opp=="7+ Goals"){
            $temp = [
               'tipo_palpite_id'=>157,
               'nome'=> '7 gols',
               'taxa'=> $gol->odds,
            ];
         }
         $odd[]=$temp;
      }

      $obj->odds = $odd;
      return $obj;
   }

   private static function golsComAmbas($ambos){
      $obj = new \stdClass;
      $obj->categoria_id = 16;
      $obj->nome = "Gols & Ambos marcam";
      $obj->tempo = "90 min";

      foreach ($ambos as $gols){
         if($gols->opp=='Over 2.5 & Yes'){
            $temp = [
               'tipo_palpite_id'=>160,
               'nome'=> '+2.5 & Sim',
               'taxa'=> $gols->odds,
            ];
         }
         if($gols->opp=='Over 2.5 & No'){
            $temp = [
               'tipo_palpite_id'=>161,
               'nome'=> '-2.5 & Não',
               'taxa'=> $gols->odds,
            ];
         }
         if($gols->opp=='Under 2.5 & Yes'){
            $temp = [
               'tipo_palpite_id'=>162,
               'nome'=> '-2.5 & Sim',
               'taxa'=> $gols->odds,
            ];
         }
         if($gols->opp=='Under 2.5 & No'){
            $temp = [
               'tipo_palpite_id'=>163,
               'nome'=> '-2.5 & Não',
               'taxa'=> $gols->odds,
            ];
         }
         $odd[]=$temp;
      }      

      $obj->odds = $odd;
      return $obj;      
   }

   private static function golsParImpar($ambos){
      $obj = new \stdClass;
      $obj->categoria_id = 7;
      $obj->nome = "Gols: Par ou Ímpar";
      $obj->tempo = "90 min";

      foreach ($ambos as $gols){
         if($gols->opp=='Even'){
            $temp = [
               'tipo_palpite_id'=>148,
               'nome'=> 'Par',
               'taxa'=> $gols->odds,
            ];
         }
         if($gols->opp=='Odd'){
            $temp = [
               'tipo_palpite_id'=>147,
               'nome'=> 'Ímpar',
               'taxa'=> $gols->odds,
            ];
         }
         $odd[]=$temp;
      }      

      $obj->odds = $odd;
      return $obj;      
   }

   private static function resultadoEGols($resultadoEGols){
      $obj = new \stdClass;
      $obj->categoria_id = 13;
      $obj->nome = "Resultado & Total de gols";
      $obj->tempo = "90 min";

      
      $odd[] =   [
            'tipo_palpite_id' => 806,
            'nome' => 'Casa & +2.5',
            'taxa' => $resultadoEGols[0]->odds
         ];

      $odd[] =   [
            'tipo_palpite_id' => 807,
            'nome' => 'Casa & -2.5',
            'taxa' => $resultadoEGols[1]->odds
         ];

      $odd[] =   [
            'tipo_palpite_id' => 808,
            'nome' => 'Empate & +2.5',
            'taxa' => $resultadoEGols[2]->odds
         ];

      $odd[] =   [
            'tipo_palpite_id' => 809,
            'nome' => 'Empate & -2.5',
            'taxa' => $resultadoEGols[3]->odds
         ];

      $odd[] =   [
            'tipo_palpite_id' => 810,
            'nome' => 'Fora & +2.5',
            'taxa' => $resultadoEGols[4]->odds
         ];

      $odd[] =   [
            'tipo_palpite_id' => 811,
            'nome' => 'Fora & -2.5',
            'taxa' => $resultadoEGols[5]->odds
         ];

      $obj->odds = $odd;
      return $obj; 


   }

   //Primeiro Tempo

   private static function vencedor1T($fullTime){
      $obj = new \stdClass;
      $obj->categoria_id = 101;
      $obj->nome = "Vencedor 1º Tempo";
      $obj->tempo = "0 - 45 min";

      foreach ($fullTime as $opc){
         if($opc->opp=="1"){
            $temp =[
               'tipo_palpite_id'=>101,
               'nome'=>'Casa',
               'taxa'=>$opc->odds,
            ];
         }
         if($opc->opp=="X"){
            $temp =[
               'tipo_palpite_id'=>102,
               'nome'=>'Empate',
               'taxa'=>$opc->odds,
            ];
         }
         if($opc->opp=="2"){
            $temp =[
               'tipo_palpite_id'=>103,
               'nome'=>'Fora',
               'taxa'=>$opc->odds,
            ];            
         }
         if(isset($temp)){
            $odd[]=$temp;
         }
         

      }
      $obj->odds = $odd;
      return $obj;
   }

   private static function duplaChance1T($doubleChance){
      $obj = new \stdClass;
      $obj->categoria_id = 102;
      $obj->nome = "Dupla Chance 1º Tempo";
      $obj->tempo = "0 - 45 min";

      foreach ($doubleChance as $opc){
         if($opc->opp=="12"){
            $temp = [
               'tipo_palpite_id'=>171,
               'nome'=>'Casa ou Fora',
               'taxa'=>$opc->odds,
            ];
         }
         if($opc->opp=="1X"){
            $temp = [
               'tipo_palpite_id'=>172,
               'nome'=>'Casa ou Empate',
               'taxa'=>$opc->odds,
            ];
         }
         if($opc->opp=="X2"){
            $temp = [
               'tipo_palpite_id'=>173,
               'nome'=>'Empate ou Fora',
               'taxa'=>$opc->odds,
            ];
         }
         $odd[]=$temp;
      }
      $obj->odds = $odd;
      return $obj;
   }

   private static function quantGols1T($odds){
      $obj = new \stdClass;
      $obj->categoria_id = 105;
      $obj->nome = "Total de Gols 1º Tempo";
      $obj->tempo = "0 - 45 min";

      $temp=[];
      if(isset($odds->goals->sp->first_half_goals)){
         $golsAlternativos = $odds->goals->sp->first_half_goals;
      }
      
      foreach ($golsAlternativos as $opc){
         switch ($opc->header){
            case("Over"):
               if($opc->goals=="0.5"){
                  $temp = [
                     'tipo_palpite_id'=>107,
                     'nome'=>'+0.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
               if($opc->goals=="1.5"){
                  $temp = [
                     'tipo_palpite_id'=>108,
                     'nome'=>'+1.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
               if($opc->goals=="2.5"){
                  $temp = [
                     'tipo_palpite_id'=>109,
                     'nome'=>'+2.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
               if($opc->goals=="3.5"){
                  $temp = [
                     'tipo_palpite_id'=>110,
                     'nome'=>'+3.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
            case("Under"):
               if($opc->goals=="0.5"){
                  $temp = [
                     'tipo_palpite_id'=>117,
                     'nome'=>'-0.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
               if($opc->goals=="1.5"){
                  $temp = [
                     'tipo_palpite_id'=>118,
                     'nome'=>'-1.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }   
               if($opc->goals=="2.5"){
                  $temp = [
                     'tipo_palpite_id'=>119,
                     'nome'=>'-2.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
               if($opc->goals=="3.5"){
                  $temp = [
                     'tipo_palpite_id'=>120,
                     'nome'=>'-3.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
         }
         
      }
      
      $obj->odds = $odd;
      return $obj;
   }

   private static function ambosMarcam1T($ambos){
      $obj = new \stdClass;
      $obj->categoria_id = 106;
      $obj->nome = "Ambos Marcam 1º Tempo";
      $obj->tempo = "0 - 45 min";

      foreach ($ambos as $opc){
         if($opc->opp=="Yes"){
            $temp = [
               'tipo_palpite_id'=>192,
               'nome'=>'Sim',
               'taxa'=>$opc->odds,
            ];
         }
         if($opc->opp=="No"){
            $temp = [
               'tipo_palpite_id'=>193,
               'nome'=>'Não',
               'taxa'=>$opc->odds,
            ];
         }
         $odd[]=$temp;
      }

      $obj->odds = $odd;
      return $obj;
   }

   private static function placarExato1T($placarExato){
      $obj = new \stdClass;
      $obj->categoria_id = 108;
      $obj->nome = "Placar Exato 1T";
      $obj->tempo = "0 - 45 min";


      foreach ($placarExato as $opc){
         
         if($opc->header=="Home"){
            switch ($opc->opp){
               case("1-0"):
                  $temp =[
                     'tipo_palpite_id'=>610,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("2-0"):
                  $temp =[
                     'tipo_palpite_id'=>620,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("2-1"):
                  $temp =[
                     'tipo_palpite_id'=>621,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("3-0"):
                  $temp =[
                     'tipo_palpite_id'=>630,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("3-1"):
                  $temp =[
                     'tipo_palpite_id'=>631,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("3-2"):
                  $temp =[
                     'tipo_palpite_id'=>632,
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
                     'tipo_palpite_id'=>600,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("1-1"):
                  $temp =[
                     'tipo_palpite_id'=>611,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("2-2"):
                  $temp =[
                     'tipo_palpite_id'=>622,
                     'nome'=> $opc->opp,
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("3-3"):
                  $temp =[
                     'tipo_palpite_id'=>633,
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
                     'tipo_palpite_id'=>601,
                     'nome'=> '0-1',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("2-0"):
                  $temp =[
                     'tipo_palpite_id'=>602,
                     'nome'=> '0-2',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("2-1"):
                  $temp =[
                     'tipo_palpite_id'=>612,
                     'nome'=> '1-2',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("3-0"):
                  $temp =[
                     'tipo_palpite_id'=>603,
                     'nome'=> '0-3',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("3-1"):
                  $temp =[
                     'tipo_palpite_id'=>613,
                     'nome'=> '1-3',
                     'taxa'=> $opc->odds,
                  ];
                  break;
               case("3-2"):
                  $temp =[
                     'tipo_palpite_id'=>623,
                     'nome'=> '2-3',
                     'taxa'=> $opc->odds,
                  ];
                  break;               
            }            
         }
         if( isset($temp) ){
            $odd[]=$temp;
         }
      }

      $obj->odds = $odd;
      return $obj;
   }

   //Segundo Tempo
   private static function vencedor2T($fullTime){
      $obj = new \stdClass;
      $obj->categoria_id = 201;
      $obj->nome = "Vencedor 2º Tempo";
      $obj->tempo = "45 - 90 min";

      foreach ($fullTime as $opc){
         if($opc->opp=="1"){
            $temp =[
               'tipo_palpite_id'=>104,
               'nome'=>'Casa',
               'taxa'=>$opc->odds,
            ];
         }
         if($opc->opp=="X"){
            $temp =[
               'tipo_palpite_id'=>105,
               'nome'=>'Empate',
               'taxa'=>$opc->odds,
            ];
         }
         if($opc->opp=="2"){
            $temp =[
               'tipo_palpite_id'=>106,
               'nome'=>'Fora',
               'taxa'=>$opc->odds,
            ];            
         }
         $odd[]=$temp;

      }
      $obj->odds = $odd;
      return $obj;
   }

   private static function ambosMarcam2T($ambos){
      $obj = new \stdClass;
      $obj->categoria_id = 206;
      $obj->nome = "Ambos Marcam 2º Tempo";
      $obj->tempo = "45 - 90 min";

      foreach ($ambos as $opc){
         if($opc->opp=="Yes"){
            $temp = [
               'tipo_palpite_id'=>194,
               'nome'=>'Sim',
               'taxa'=>$opc->odds,
            ];
         }
         if($opc->opp=="No"){
            $temp = [
               'tipo_palpite_id'=>195,
               'nome'=>'Não',
               'taxa'=>$opc->odds,
            ];
         }
         $odd[]=$temp;
      }

      $obj->odds = $odd;
      return $obj;
   }

   private static function quantGols2T($odds){
      $obj = new \stdClass;
      $obj->categoria_id = 205;
      $obj->nome = "Total de Gols 2º Tempo";
      $obj->tempo = "45 - 90 min";

      $temp=[];

      $golsAlternativos = $odds;
      
      foreach ($golsAlternativos as $opc){
         switch ($opc->header){
            case("Over"):
               if($opc->goals=="0.5"){
                  $temp = [
                     'tipo_palpite_id'=>207,
                     'nome'=>'+0.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
               if($opc->goals=="1.5"){
                  $temp = [
                     'tipo_palpite_id'=>208,
                     'nome'=>'+1.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
               if($opc->goals=="2.5"){
                  $temp = [
                     'tipo_palpite_id'=>209,
                     'nome'=>'+2.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
               if($opc->goals=="3.5"){
                  $temp = [
                     'tipo_palpite_id'=>210,
                     'nome'=>'+3.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
            case("Under"):
               if($opc->goals=="0.5"){
                  $temp = [
                     'tipo_palpite_id'=>217,
                     'nome'=>'-0.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
               if($opc->goals=="1.5"){
                  $temp = [
                     'tipo_palpite_id'=>218,
                     'nome'=>'-1.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }   
               if($opc->goals=="2.5"){
                  $temp = [
                     'tipo_palpite_id'=>219,
                     'nome'=>'-2.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
               if($opc->goals=="3.5"){
                  $temp = [
                     'tipo_palpite_id'=>220,
                     'nome'=>'-3.5',
                     'taxa'=>$opc->odds,
                  ];
                  $odd[]=$temp;break;
               }
         }
         
      }
      
      $obj->odds = $odd;
      return $obj;
   }

   //Tempo Misto
   private static function casaMaisGols($odds){
      $obj = new \stdClass;
      $obj->categoria_id = 18;
      $obj->nome = "Casa - Tempo com Mais Gols";
      $obj->tempo = "Misto";
         
      $odd[]=[
         'tipo_palpite_id'=>180,
         'nome'=>'Tempo 1',
         'taxa'=>$odds[0]->odds
      ];

      $odd[]=[
         'tipo_palpite_id'=>181,
         'nome'=>'Tempo 2',
         'taxa'=>$odds[1]->odds
      ];

      $odd[]=[
         'tipo_palpite_id'=>182,
         'nome'=>'Empate',
         'taxa'=>$odds[2]->odds
      ];

      $obj->odds = $odd;
      return $obj;
   }

   private static function ForaMaisGols($odds){
      $obj = new \stdClass;
      $obj->categoria_id = 19;
      $obj->nome = "Fora - Tempo com Mais Gols";
      $obj->tempo = "Misto";
         
      $odd[]=[
         'tipo_palpite_id'=>183,
         'nome'=>'Tempo 1',
         'taxa'=>$odds[0]->odds
      ];

      $odd[]=[
         'tipo_palpite_id'=>184,
         'nome'=>'Tempo 2',
         'taxa'=>$odds[1]->odds
      ];

      $odd[]=[
         'tipo_palpite_id'=>185,
         'nome'=>'Empate',
         'taxa'=>$odds[2]->odds
      ];

      $obj->odds = $odd;
      return $obj;
   }
}