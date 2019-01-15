@extends('componentes.pagina')

@section('content')
<div class="container">
	<div class="lista-ligas">
		<div class="row">
			<div class="col-2"><b>ID</b></div>
			<div class="col-2"><b>Prioridade</b></div>
			<div class="col-8"><b>Nome</b></div>
		</div>

	@foreach($ligas as $liga)
		<div class="row">
			<div class="col-2"><a href="{{ route('adminliga', ['id' => $liga->id]) }}">{{ $liga->id }}</a></div>
			<div class="col-2">{{ $liga->is_top_list }}</div>
			<div class="col-8">{{ $liga->nome }}</div>
		</div>
	@endforeach

	</div>
</div>
@endsection