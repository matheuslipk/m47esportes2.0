@extends('componentes.pagina')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col">
			
		</div>
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
		<div class="col">
			
		</div>
		
	</div>
</div>
@endsection