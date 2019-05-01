@extends('componentes.pagina')

@section('titulo')
Admin - Bolão
@endsection

@section('content')

<div class="container">
	<div class="row justify-content-center">
		<div class="col-sm-8">
			<h4>Lista dos bolões</h4>
			<table class="table">
				<tbody style="font-size: 13px">
					@foreach( $bolaos as $bolao )
						<tr>
							<td>
								<a href="{{ route('admin_showbolao', ['id' => $bolao->id]) }}">{{ $bolao->nome }}</a><br>
								Quant Eventos: {{ $bolao->quant_eventos }}<br>
								Valor do bolão: R$ {{ $bolao->valor_aposta }}<br>
								Comissão Agente: {{ ($bolao->comissao_agente)*100 }}%
							</td>
							<td>
								<br>
								Início: {{ $bolao->data_abertura }}<br>
								Fim: {{ $bolao->data_fechamento }}<br>								
								Comissão Casa: {{ ($bolao->comissao_casa)*100 }}%
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
			
</div>

@endsection