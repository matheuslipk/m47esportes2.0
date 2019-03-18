@extends('componentes.pagina')

@section('titulo')
Admin - Bolão
@endsection

@section('content')

<div class="container">
	<div class="formulario">
		<form action="{{ route('admin_atualizarbolao', ["id" => $bolao->id]) }}" method="post">
			@method('put')
			@csrf
			<div class="row justify-content-center">
				<div class="col-12 col-md-6">
					<label>Nome</label>
					<input class="form-control" value="{{ $bolao->nome }}" name="nome" required>
				</div>	
				<div class="col-12 col-md-6">
					<label>Qnt de eventos</label>
					<input class="form-control" value="{{ $bolao->quant_eventos }}" type="number" name="quant_eventos" required>
				</div>				
			</div>
			
			<div class="row justify-content-center">					
				<div class="col-12 col-md-6">
					<label>Início das apostas</label>
					<input class="form-control" value="{{ substr($bolao->data_abertura, 0, 10)."T".substr($bolao->data_abertura, 11, 5) }}" type="datetime-local" name="data_abertura" required>
				</div>
				<div class="col-12 col-md-6">
					<label>Fim das apostas</label>
					<input class="form-control" value="{{ substr($bolao->data_fechamento, 0, 10)."T".substr($bolao->data_fechamento, 11, 5) }}" type="datetime-local" name="data_fechamento" required>
				</div>				
			</div>

			<div class="row justify-content-center">
				<div class="col-12 col-md-6">
					<label>Valor das apostas</label>
					<input class="form-control" value="{{ $bolao->valor_aposta }}" type="number" name="valor_aposta" required>
				</div>
				<div class="col-12 col-md-6">
					<label>Status bolão</label>
					<input class="form-control" value="{{ $bolao->status_id }}" type="" name="status_id" required>
				</div>			
			</div>

			<br><br>

			<div class="row justify-content-center">
				<div class="col-12 col-md-6">
					<button class="btn btn-block btn-primary">Atualizar</button>
				</div>			
			</div>			

		</form>
	</div>
</div>

@endsection