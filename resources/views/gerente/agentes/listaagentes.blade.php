@extends('componentes.pagina')

@section('content')
<div class="container-fluid">
	
	<div class="row justify-content-center">
		<div class="col-sm-8">
			<table class="table">
				<thead>
					<tr>
						<th>Nome</th>
						<th>Email</th>
						<th>Status</th>
						<th>Ações</th>
					</tr>
				</thead>
				<tbody>
					@foreach($agentes as $agente)
						<tr>
							<td>{{$agente->name}}</td>
							<td>{{$agente->email}}</td>
							<td>{{$agente->status_conta->nome}}</td>
							<td><a class="btn btn-sm btn-primary" href="{{ route('veragente_gerente', ['id' => $agente->id]) }}">Ver</a></td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>		
	</div>

	<div class="row justify-content-center">
		<div class="col-sm-4 text-center">
			<a href="{{ route('agenteregistro_gerente') }}" class="btn btn-info">Cadastrar Agente</a>
		</div>
	</div>

</div>
@endsection