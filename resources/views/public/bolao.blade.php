@extends('componentes.pagina')

@section('titulo')
#{{ $aposta->id }} - {{ $aposta->nome }}
@endsection

@section('content')
<div class="container">
	<div>
		
		<table class="table">
			<tbody>
				
				<tr>
					<th>Bolão: #{{ $aposta->id }}</th>
					<th colspan="2">Valor: R$ {{ $aposta->valor_apostado }}</th>
				</tr>

				<tr>
					<th>Agente: {{ $aposta->agente->nickname }}</th>
					<th colspan="2">Cliente: {{ $aposta->nome }}</th>
				</tr>

				@foreach($palpites as $palpite)
					@php
						$casa='';
						$empate='';
						$fora='';

						if($palpite->tipo_palpite_id == 1) $casa = "bg-success";
						if($palpite->tipo_palpite_id == 2) $empate = "bg-success";
						if($palpite->tipo_palpite_id == 3) $fora = "bg-success";
					@endphp
					<tr>
						<td class="{{ $casa }}">{{ $palpite->evento->time1->nome }}</td>
						<td class="{{ $empate }}">Empate</td>
						<td class="{{ $fora }}">{{ $palpite->evento->time2->nome }}</td>
					</tr>
				@endforeach

				<tr>
					<th>Acertos: {{ $aposta->quant_acertos }}</th>
					<th>Erros:  {{ $aposta->quant_erros }}</th>
				</tr>

			</tbody>
		</table>

	</div>
</div>
@endsection