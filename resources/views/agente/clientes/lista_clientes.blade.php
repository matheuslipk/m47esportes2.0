@extends('componentes.pagina')

@section('content')
	<div class="container">

		<div class="row justify-content-center">
			<h3>Seus clientes cadastrados</h3>
		</div>

		<table class="table">
			<thead>
				<tr>
					<th colspan="3"><a class="btn btn-primary" href="{{ route('agente.novocliente') }}">Cadastrar cliente</a></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th>Nome</th>
					<th>Telefone</th>
					<th>Ações</th>
				</tr>
				@foreach( $clientes as $cliente )
					<tr>
						<td>{{ $cliente->nome }}</td>
						<td>{{ $cliente->telefone }}</td>
						<td><a class="btn btn-sm btn-warning" href="{{ route('agente.editarcliente', ['id'=>$cliente->id]) }}">Editar</a></td>
					</tr>
				@endforeach	
			</tbody>
		</table>

		<div class="row justify-content-center">
			<nav aria-label="Navegação de página exemplo">
			  <ul class="pagination">

			  	@if( $request->page==0 || !is_numeric($request->page) )
			  	<li class="page-item active"><a class="page-link" href="{{ route('agente.clientes', ['page' => 0]) }}">Início</a></li>
			  	@else
			  	<li class="page-item"><a class="page-link" href="{{ route('agente.clientes', ['page' => 0]) }}">Início</a></li>
			  	@endif

			  	@for( $i=1; $i<$quantPaginas; $i++ )
			  		@php
			  		$atual = "";
			  		if( $i == $request->page ){
			  			$atual = "active";
			  		}

			  		@endphp 		
			  		<li class="page-item {{ $atual }}"><a class="page-link" href="{{ route('agente.clientes', ['page' => $i]) }}">{{ $i }}</a></li>
			  	@endfor

			  </ul>
			</nav>
		</div>
	
			
	</div>
	

@endsection

@section('css')
<style type="text/css">

</style>
@endsection

@section('javascript')
<script type="text/javascript">

</script>
@endsection