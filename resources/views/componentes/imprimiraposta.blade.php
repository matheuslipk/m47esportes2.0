<section>
	<div class="text-aposta">
		<textarea class='' readonly id='textAposta' style='height: 0px; width: 0px' >
			*****************************
			***  m47esportes.com.br  ****
			*****************************
			Aposta: {{$aposta->id}}
			Criada em: {{$aposta->data_aposta}}
			Validada em: {{$aposta->data_validacao}}
			Nome: {{$aposta->nome}}
			Agente: {{$aposta->agente->nickname}}
			-------------------------

		@foreach($aposta->palpites as $palpite)
			{{$palpite->evento->liga->nome}}
			{{$palpite->evento->time1->nome}} x {{$palpite->evento->time2->nome}}
			{{$palpite->evento->data}}
			{{$palpite->tipo_palpite->cat_palpite->nome}}
			{{$palpite->tipo_palpite->nome}} : {{$palpite->cotacao}}
			-------------------------
		@endforeach

			Cota Total: {{$aposta->cotacao_total}}
			Valor Apostado: R$ {{$aposta->valor_apostado}}
			Possíveis ganhos: R$ {{$aposta->premiacao}}

		</textarea>
	</div>
</section>