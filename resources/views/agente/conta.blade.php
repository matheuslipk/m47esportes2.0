@extends('componentes.pagina')

@section('content')
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<br>
			<div class="card">
				<div class="card-header">{{ $agente->name }}</div>

				<div class="card-body">
					<form method="post">
						@csrf
						<div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Telefone</label>

                            <div class="col-md-6">
                                <input disabled id="telefone" class="form-control" type="text" name="telefone" value="{{ $agente->telefone }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Email</label>

                            <div class="col-md-6">
                                <input disabled id="email" class="form-control" type="email" name="email" value="{{ $agente->email }}">
                            </div>
                        </div>
                       
                        <div class="form-group row">
                        	<div class="col-6">
                        		<button id="btn-editar" type="button" class="btn btn-block" onclick="ativarFormulario()">Editar</button>
                        	</div>
                        	<div class="col-6">
                        		<button id="btn-atualizar" disabled class="btn btn-warning btn-block">Atualizar</button>
                        	</div>	                            
                        </div>

					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="row justify-content-center">
		<div class="col-md-8">
			<br>
			<div class="card">
				<div class="card-header">Mudar a Senha</div>

				<div class="card-body">
					<form method="post" action="{{route("agente-atualizarsenha")}}" id="form-atualizar-senha">
						@csrf
						<div class="form-group row">
                            <div class="col-md-12">
                                <input placeholder="Senha Antiga" id="senha-antiga" class="form-control text-center" type="password" name="senha-antiga">
                            </div>
                        </div>

                        <div class="form-group row">
                        	<div class="col-sm-6">
                                <input placeholder="Nova senha" id="nova-senha" class="form-control" type="password" name="nova-senha">
                            </div>
                            <div class="col-sm-6">
                                <input placeholder="Confirmar senha" id="confirmar-senha" class="form-control" type="password" name="confirmar-senha">
                            </div>
                        </div>
                       
                        <div class="form-group row">
                        	<div class="col-12">
                        		<button type="button" id="btn-mudar-senha" class="btn btn-danger btn-block" onclick="verificarCamposSenha()">Mudar senha</button>
                        	</div>	                            
                        </div>

					</form>
				</div>
			</div>
		</div>
	</div>

</div>
@endsection


@section('javascript')
<script>
	function ativarFormulario(){
        $("#telefone").attr('disabled', false);
		$("#email").attr('disabled', false);
		$("#btn-atualizar").attr('disabled', false);
		$("#btn-editar").attr('disabled', true);
	}

	function verificarCamposSenha(){
		var senhaAntiga = $("#senha-antiga").val();
		var novaSenha = $("#nova-senha").val();
		var confirmarSenha = $("#confirmar-senha").val();

		if(novaSenha.length < 6){
			alert("Sua nova senha deve ter no mínimo 6 caracteres");
			return;
		}

		if(novaSenha != confirmarSenha){
			alert("O campo de nova senha e confirmar senha devem ser iguais");
			return;
		}
		submeterForm();

	}

	function submeterForm(){
		$("#form-atualizar-senha").submit();
	}
</script>
@endsection

@section('css')
<style>

</style>
@endsection