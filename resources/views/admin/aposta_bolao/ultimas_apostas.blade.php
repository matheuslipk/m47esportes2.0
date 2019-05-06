@extends('componentes.pagina')

@section('content')
<div class="container">
	{{-- Se for a tela de apostas --}}
	@if( isset($apostas) )
	<div class="row justify-content-center">
		<h3>Apostas</h3>
	</div>
	<div class="row justify-content-center">
		
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th>Cliente</th>
					<th>Agente</th>
					<th>Acertos</th>
					<th>Erros</th>
				</tr>
			</thead>
			<tbody>
				@foreach($apostas as $aposta)

				<tr>
					<td><a href="{{ route('admin.viewaposta', ['aposta'=>$aposta->id]) }}">{{ $aposta->id }}</a></td>
					<td>{{ $aposta->nome }}</td>
					<td>{{ $aposta->agente->nickname }}</td>
					<td>{{ $aposta->quant_acertos }}</td>
					<td>{{ $aposta->quant_erros }}</td>
				</tr>

				@endforeach
			</tbody>
		</table>

	</div>
	@endif

	{{-- Se for a tela de bolãos --}}
	@if( isset($boloes) )
	<div class="row justify-content-center">
		<h3>Bolões</h3>
	</div>
	<div class="row justify-content-center">
		
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th>Bolão</th>
					<th>Premiação Atualizada</th>
				</tr>
			</thead>
			<tbody>
				@foreach($boloes as $bolao)

				<tr>
					<td><a href="{{ route('admin.apostaboloes', ['bolao_id'=>$bolao->id]) }}">{{ $bolao->id }}</a></td>
					<td>{{ $bolao->nome }}</td>
					<td>R$ {{ number_format($bolao->premiacao, 2) }}</td>
				</tr>

				@endforeach
			</tbody>
		</table>

	</div>
	@endif



</div>
@endsection