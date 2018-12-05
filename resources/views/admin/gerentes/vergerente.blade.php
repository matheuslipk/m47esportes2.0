@extends('componentes.pagina')

@section('content')
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">Informações do gerente</div>

				<div class="card-body">
					<form action="{{ route('salvargerente', ['id' => $gerente->id]) }}">
						@csrf
						<input style="display: none" type="number" name="gerente_id" value="{{$gerente->id}}">

						<div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Nome</label>

                            <div class="col-md-6">
                                <input disabled id="name" type="text" class="form-control" name="name" value="{{$gerente->name}}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="telefone" class="col-md-4 col-form-label text-md-right">Telefone</label>

                            <div class="col-md-6">
                                <input disabled id="telefone" class="form-control" name="telefone" value="{{$gerente->telefone}}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                            <div class="col-md-6">
                                <input disabled id="email" type="email" class="form-control" name="email" value="{{$gerente->email}}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="status_conta" class="col-md-4 col-form-label text-md-right">Status da conta</label>

                            <div class="col-md-6">
                                <select id="status_conta" disabled class="form-control" name="status_conta">
                                	@if($gerente->status_conta_id == 1)
                                		<option selected value="1">Ativa</option>
                                	@else
                                		<option value="1">Ativa</option>
                                	@endif

                                	@if($gerente->status_conta_id == 2)
                                		<option selected value="2">Inativa</option>
                                	@else
                                		<option value="2">Inativa</option>
                                	@endif

                                	@if($gerente->status_conta_id == 3)
                                		<option selected value="3">Suspensa</option>
                                	@else
                                		<option value="3">Suspensa</option>
                                	@endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                        	<div class="col-4">
                        		<button disabled id="btn-cancelar" class="btn btn-warning" type="button" onclick="desativarFormulario()">Cancelar</button>
                        	</div>

                        	<div class="col-4">
                        		<button id="btn-editar" class="btn" type="button" onclick="ativarFormulario()">Editar</button>
                        	</div>

                        	<div class="col-4">
	                            <button disabled id="btn-salvar" class="btn btn-primary" type="submit">Salvar</button>
                        	</div>
	                            
                        </div>

					</form>
				</div>
			</div>
		</div>		
	</div>

    @if(count($agentes) >= 1)
    <div class="row justify-content-center">
        <div class="col-md-8">
            <table class="table">
                <thead>
                    <tr><th colspan="3">LISTA DOS AGENTES</th></tr>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($agentes as $agente)
                        <tr>
                            <td>#{{ $agente->id }}</td>
                            <td>{{ $agente->name }}</td>
                            <td>{{ $agente->email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection

@section('javascript')
<script type="text/javascript">
	function ativarFormulario(){
		$("#name").attr('disabled', false);
        $("#telefone").attr('disabled', false);
		$("#email").attr('disabled', false);
		$("#status_conta").attr('disabled', false);
		$("#btn-salvar").attr('disabled', false);
		$("#btn-editar").attr('disabled', true);
		$("#btn-cancelar").attr('disabled', false);
	}

	function desativarFormulario(){
		$("#name").attr('disabled', true);
        $("#telefone").attr('disabled', true);
		$("#email").attr('disabled', true);
		$("#status_conta").attr('disabled', true);
		$("#btn-salvar").attr('disabled', true);
		$("#btn-editar").attr('disabled', false);
		$("#btn-cancelar").attr('disabled', true);
	}
</script>
@endsection