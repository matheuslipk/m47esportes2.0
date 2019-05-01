@extends('componentes.pagina')

@section('titulo')
Cadastro de Eventos exclusivos de bolão
@endsection


@section('content')
<div class="container">
	<div class="formulario">
		<form action="{{ route('admin.evento_bolaos.store') }}" method="post">
			@csrf
			<div class="row justify-content-center">
				<div class="col-12 col-md-6">
					<label>Liga</label>
					<select class="form-control" name="liga_id">
						@foreach( $ligas as $liga )
							<option value="{{$liga->id}}">{{$liga->nome}}</option>
						@endforeach
					</select>
				</div>	
				<div class="col-12 col-md-6">
					<label>Data</label>
					<input class="form-control" type="datetime-local" name="data_evento" value="{{date('Y-m-d', time())}}T12:00" required>
				</div>				
			</div>
			
			<div class="row justify-content-center">					
				<div class="col-12 col-md-6">
					<label>Time 1</label>
					<select class="form-control" name="time1_id">
						@foreach($times as $time)
						<option value="{{$time->id}}">{{$time->nome}}</option>
						@endforeach
					</select>
				</div>
				<div class="col-12 col-md-6">
					<label>Time 2</label>
					<select class="form-control" name="time2_id">
						@foreach($times as $time)
						<option value="{{$time->id}}">{{$time->nome}}</option>
						@endforeach
					</select>
				</div>				
			</div>

			<br><br>

			<div class="row justify-content-center">
				<div class="col-12 col-md-6">
					<button class="btn btn-block btn-primary">Cadastrar</button>
				</div>			
			</div>			

		</form>
	</div>
</div>
@endsection