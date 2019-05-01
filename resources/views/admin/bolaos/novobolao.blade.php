@extends('componentes.pagina')

@section('titulo')
Admin - Bolão
@endsection

@section('content')

<div class="container">
	<div class="formulario">
		<form action="{{ route('admin_novobolao') }}" method="post">
			@csrf
			<div class="row justify-content-center">
				<div class="col-12 col-md-6">
					<label>Nome</label>
					<input class="form-control" type="" name="nome" required>
				</div>	
				<div class="col-12 col-md-6">
					<label>Qnt de eventos</label>
					<input class="form-control" type="number" name="quant_eventos" required>
				</div>				
			</div>
			
			<div class="row justify-content-center">					
				<div class="col-12 col-md-6">
					<label>Início das apostas</label>
					<input class="form-control" type="datetime-local" name="data_abertura" required>
				</div>
				<div class="col-12 col-md-6">
					<label>Fim das apostas</label>
					<input class="form-control" type="datetime-local" name="data_fechamento" required>
				</div>				
			</div>

			<div class="row justify-content-center">					
				<div class="col-12 col-md-6">
					<label>Comissão Agente</label>
					<input class="form-control" type="number" step="0.01" min="0" max="0.2" name="comissao_agente" required>
				</div>
				<div class="col-12 col-md-6">
					<label>Comissão Casa</label>
					<input class="form-control" type="number" step="0.01" min="0" max="0.2" name="comissao_casa" required>
				</div>				
			</div>

			<div class="row justify-content-center">
				<div class="col-12 col-md-6">
					<label>Valor das apostas</label>
					<input class="form-control" type="number" name="valor_aposta" required>
				</div>
				<div class="col-12 col-md-6">
					<label>Status bolão</label>
					<select name="status_id" class="form-control">
						<option value="1">Válido</option>
						<option value="0">Inválido</option>
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