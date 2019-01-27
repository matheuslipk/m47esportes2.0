@extends('componentes.pagina')

@section('content')
	<div class="row justify-content-center">
		<h4>Erro: {{$erro->id}} - {{$erro->nome}}</h4>
	</div>
@endsection
