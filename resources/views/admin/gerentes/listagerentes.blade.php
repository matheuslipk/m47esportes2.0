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
					@foreach($gerentes as $gerente)
						<tr>
							<td>{{$gerente->name}}</td>
							<td>{{$gerente->email}}</td>
							<td>{{$gerente->status_conta->nome}}</td>
							<td><a class="btn btn-sm btn-primary" href="{{ route('editargerente', ['id' => $gerente->id]) }}">Ver</a></td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>		
	</div>

	<div class="row justify-content-center">
		<div class="col-sm-4 text-center">
			<a href="{{ route('gerenteregistro') }}" class="btn btn-info">Cadastrar Gerente</a>
		</div>
	</div>

</div>
@endsection