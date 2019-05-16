@extends('componentes.pagina')

@section('titulo')
Cadastro de Eventos exclusivos de bolão
@endsection


@section('content')
<div class="container">
	<div class="formulario">
			
		@foreach($eventos as $evento)
		<form method="post" action="{{ route('admin.bolao.eventos.atualizar') }}">
			@csrf
			@php
			$score_t1 = null;
			$score_t2 = null;
			if( isset($evento->score) ){
				$score_t1 = $evento->score->score_time1;
				$score_t2 = $evento->score->score_time2;
			}
			@endphp


			<input type="hidden" name="evento_id" value="{{ $evento->id }}">
			<div class="row justify-content-center">					
				<div class="col-4">
					<label>{{ $evento->time1->nome }}</label>
					<input style="width: 50px" type="number" name="score_time1" required value="{{ $score_t1 }}">
				</div>
				<div class="col-4">
					<input style="width: 50px" type="number" name="score_time2" required value="{{ $score_t2 }}">
					<label>{{ $evento->time2->nome }}</label>
				</div>

				<div class="col-4">
					<button class="btn btn-sm btn-primary">Atualizar</button>
				</div>				
			</div>
		</form>
		@endforeach			

	</div>
</div>
@endsection