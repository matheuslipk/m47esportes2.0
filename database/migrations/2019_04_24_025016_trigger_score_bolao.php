<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TriggerScoreBolao extends Migration
{

    public function up(){
        DB::unprepared("CREATE TRIGGER atualizar_palpites AFTER INSERT ON `score_bolaos` FOR EACH ROW

BEGIN
  DECLARE varGolsTime1, varGolsTime2 INT(11);
  SELECT s.score_time1, s.score_time2 INTO varGolsTime1, varGolsTime2 FROM score_bolaos s WHERE s.evento_id=new.evento_id;
  
  UPDATE palpite_bolaos SET situacao_palpite_id=2 WHERE evento_id=new.evento_id;
  
  IF( varGolsTime1 > varGolsTime2 )THEN
    UPDATE palpite_bolaos SET situacao_palpite_id=1 WHERE evento_id=new.evento_id AND tipo_palpite_id=1;
  ELSEIF ( varGolsTime1 = varGolsTime2) THEN
    UPDATE palpite_bolaos SET situacao_palpite_id=1 WHERE evento_id=new.evento_id AND tipo_palpite_id=2;
  ELSEIF ( varGolsTime1 < varGolsTime2) THEN
    UPDATE palpite_bolaos SET situacao_palpite_id=1 WHERE evento_id=new.evento_id AND tipo_palpite_id=3;
  END IF;
  
END");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        DB::unprepared('DROP TRIGGER IF EXISTS atualizar_palpites');
    }
}
