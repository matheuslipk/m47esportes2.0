<section>
	<div class="text-aposta">
		<textarea class='' readonly id='textAposta' style='height: 0px; width: 0px' >
			*****************************
			***  m47esportes.com.br  ****
			*****************************
			Aposta: {{$aposta->id}}&#10
			Criada em: {{$aposta->data_aposta}}&#10
			Validada em: {{$aposta->data_validacao}}&#10
			Nome: {{$aposta->nome}}&#10
			Agente: {{$aposta->agente->nickname}}&#10
			-------------------------

		@foreach($aposta->palpites as $palpite)
			Evento: {{$palpite->evento->id}}&#10
			{{$palpite->evento->time1->nome}} x {{$palpite->evento->time2->nome}}&#10
			{{$palpite->evento->data}}&#10
			{{$palpite->tipo_palpite->cat_palpite->nome}}&#10
			{{$palpite->tipo_palpite->nome}} : {{$palpite->cotacao}}&#10
			-------------------------
		@endforeach
	
			Cota Total: {{$aposta->cotacao_total}}&#10
			Valor Apostado: R$ {{$aposta->valor_apostado}}&#10
			Possíveis ganhos: R$ {{$aposta->premiacao}}&#10

		</textarea>
	</div>
</section>