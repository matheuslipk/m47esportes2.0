@extends('componentes.pagina')

@section('content')
	<div class="container">

		<div class="row justify-content-center">
			<div class="col-md-8">
				<br>
				<div class="card">
					<div class="card-header">Novo cliente</div>

					<div class="card-body">
						<form method="post" action="{{ route('agente.novocliente') }}">
							@csrf
							<div class="form-group row">
	                            <label class="col-md-4 col-form-label text-md-right">Nome</label>

	                            <div class="col-md-6">
	                                <input id="nome" class="form-control" type="text" name="nome">
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label class="col-md-4 col-form-label text-md-right">Telefone</label>

	                            <div class="col-md-6">
	                                <input id="telefone" type="number" class="form-control" name="telefone">
	                            </div>
	                        </div>
	                       
	                        <div class="form-group row">
	                        	<div class="col-12">
	                        		<button id="btn-editar" type="button" onclick="cadastrarCliente()" class="btn btn-block btn-primary" >Salvar</button>
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
<script type="text/javascript">
	function cadastrarCliente(){
		$.post('{{ route('agente.novocliente') }}', {
			nome: $('#nome').val(),
			telefone: $('#telefone').val()
		}).done(function (resposta){
			alert('Cliente cadastrado com sucesso!!');
			$('#nome').val('');
			$('#telefone').val('');
		});
	}
</script>
@endsection