@extends('componentes.pagina')

@section('content')
	<div class="container">

		<div class="row justify-content-center">
			<div class="col-md-8">
				<br>
				<div class="card">
					<div class="card-header">Atualizar informações do cliente</div>

					<div class="card-body">
						<form method="post" action="{{ route('agente.novocliente') }}">
							@csrf
							<input id="id" type="hidden" name="id" value="{{ $cliente->id }}">

							<div class="form-group row">
	                            <label class="col-md-4 col-form-label text-md-right">Nome</label>

	                            <div class="col-md-6">
	                                <input id="nome" class="form-control" type="text" name="nome" value="{{ $cliente->nome }}">
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label class="col-md-4 col-form-label text-md-right">Telefone</label>

	                            <div class="col-md-6">
	                                <input id="telefone" class="form-control" name="telefone" value="{{ $cliente->telefone }}">
	                            </div>
	                        </div>
	                       
	                        <div class="form-group row">
	                        	<div class="col-12">
	                        		<button id="btn-editar" type="button" onclick="atualizarCliente()" class="btn btn-block btn-primary" >Atualizar</button>
	                        	</div>                            
	                        </div>

	                        <div class="form-group row justify-content-center">
	                        	<div class="col-12">
	                        		<a href="{{ route('agente.clientes') }}">Ver a lista de clientes</a>
	                        	</div>                            
	                        </div>	

						</form>
					</div>
				</div>
			</div>
		</div>				
			
	</div>	

@endsection

@section('css')
<style type="text/css">

</style>
@endsection

@section('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>


<script type="text/javascript">

	$(document).ready(function (){
		$('#telefone').mask('(00) 00000-0000');
	});


	function atualizarCliente(){
		$.post('{{ route('agente.editarcliente', ['id' => $cliente->id]) }}', {
			id: $('#id').val(),
			nome: $('#nome').val(),
			telefone: $('#telefone').val()
		}).done(function (resposta){
			alert('Informações atualizadas com sucesso!!');
		}).fail(function(response){
			alert(response.responseText);
		});
	}
</script>
@endsection