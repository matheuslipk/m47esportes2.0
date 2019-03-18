@extends('componentes.pagina')

@section('titulo')
Admin - Bolão
@endsection

@section('content')

<div class="container">
	<div class="row justify-content-center">
		<div class="col-8">
			<h4>Lista dos bolões</h4>
			<table class="table">
				<tbody>
					@foreach( $bolaos as $bolao )
						<tr>
							<td>
								<a href="{{ route('admin_showbolao', ['id' => $bolao->id]) }}">{{ $bolao->nome }}</a><br>
								Quant Eventos: {{ $bolao->quant_eventos }}<br>
								Valor do bolão: R$ {{ $bolao->valor_aposta }}
							</td>
							<td>
								Início: {{ $bolao->data_abertura }}<br>
								Fim: {{ $bolao->data_fechamento }}<br>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
			
</div>

@endsection