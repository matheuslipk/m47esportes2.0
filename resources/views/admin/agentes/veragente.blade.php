@extends('componentes.pagina')

@section('content')
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header"><h3>Informações do agente</h3></div>

				<div class="card-body">
					<form method="post" action="{{ route('salvaragente', ['id' => $agente->id]) }}">
						@csrf
						<input style="display: none" type="number" name="agente_id" value="{{$agente->id}}">

						<div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Nome</label>

                            <div class="col-md-6">
                                <input disabled id="name" type="text" class="form-control" name="name" value="{{$agente->name}}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="telefone" class="col-md-4 col-form-label text-md-right">Telefone</label>

                            <div class="col-md-6">
                                <input disabled id="telefone" class="form-control" name="telefone" value="{{$agente->telefone}}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                            <div class="col-md-6">
                                <input disabled id="email" type="email" class="form-control" name="email" value="{{$agente->email}}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="status_conta" class="col-md-4 col-form-label text-md-right">Status da conta</label>

                            <div class="col-md-6">
                                <select id="status_conta" disabled class="form-control" name="status_conta">
                                	@if($agente->status_conta_id == 1)
                                		<option selected value="1">Ativa</option>
                                	@else
                                		<option value="1">Ativa</option>
                                	@endif

                                	@if($agente->status_conta_id == 2)
                                		<option selected value="2">Inativa</option>
                                	@else
                                		<option value="2">Inativa</option>
                                	@endif

                                	@if($agente->status_conta_id == 3)
                                		<option selected value="3">Suspensa</option>
                                	@else
                                		<option value="3">Suspensa</option>
                                	@endif

                                    @if($agente->status_conta_id == 4)
                                        <option selected value="4">Limitada</option>
                                    @else
                                        <option value="4">Limitada</option>
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
    <br>
    <!--Inicio configuração agente-->
    @if( is_object($configAgente) )

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3>Configurações de comissão do agente</h3></div>

                <div class="card-body">
                    <form method="post" action="{{ route('editarconfig_agente', ['id' => $agente->id]) }}">
                        @csrf
                        <h4>Comissão com a cota da aposta: </h4>

                        {{-- INÍCIO Novas atualizações --}}

                        <div class="form-group row">
                            <label for="cota1" class="col-md-4 col-form-label text-md-right">Odd Mínima de aposta</label>

                            <div class="col-md-6">
                                <input disabled id="oddMinima" type="number" step="0.5" class="form-control" name="oddMinima" value="{{ ($configAgente->where('tipo_config_id', App\TipoConfig::ODD_MINIMA)
                                        ->first()->valor) }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cota1" class="col-md-4 col-form-label text-md-right">Odd Máxima de aposta</label>

                            <div class="col-md-6">
                                <input disabled id="oddMaxima" type="number" class="form-control" name="oddMaxima" value="{{ ($configAgente->where('tipo_config_id', App\TipoConfig::ODD_MAXIMA)
                                        ->first()->valor) }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cota1" class="col-md-4 col-form-label text-md-right">Valor mínimo apostado</label>

                            <div class="col-md-6">
                                <input disabled id="minApostado" type="number" class="form-control" name="minApostado" value="{{ ($configAgente->where('tipo_config_id', App\TipoConfig::VALOR_MIN_APOSTA)
                                        ->first()->valor) }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cota1" class="col-md-4 col-form-label text-md-right">Valor máximo apostado</label>

                            <div class="col-md-6">
                                <input disabled id="maxApostado" type="number" class="form-control" name="maxApostado" value="{{ ($configAgente->where('tipo_config_id', App\TipoConfig::VALOR_MAX_APOSTA)
                                        ->first()->valor) }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cota1" class="col-md-4 col-form-label text-md-right">Qnt mínima palpites</label>

                            <div class="col-md-6">
                                <input disabled id="minPalpites" type="number" class="form-control" name="minPalpites" value="{{ ($configAgente->where('tipo_config_id', App\TipoConfig::QUANT_MIN_PALPITES)
                                        ->first()->valor) }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cota1" class="col-md-4 col-form-label text-md-right">Qnt máxima palpites</label>

                            <div class="col-md-6">
                                <input disabled id="maxPalpites" type="number" class="form-control" name="maxPalpites" value="{{ ($configAgente->where('tipo_config_id', App\TipoConfig::QUANT_MAX_PALPITES)
                                        ->first()->valor) }}" required autofocus>
                            </div>
                        </div>

                        {{-- FIM Novas atualizações --}}

                        <div class="form-group row">
                            <label for="cota1" class="col-md-4 col-form-label text-md-right"> >= 2 e < 6 </label>

                            <div class="col-md-6">
                                <input disabled id="cota1" type="number" min="7.5" max="18" step="0.5" class="form-control" name="cota1" value="{{ ($configAgente->where('tipo_config_id', App\TipoConfig::COMISSAO_1)
                                        ->first()->valor)*100 }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cota2" class="col-md-4 col-form-label text-md-right"> >= 6 & < 12 </label>

                            <div class="col-md-6">
                                <input disabled id="cota2" type="number" min="7.5" max="18" step="0.5" class="form-control" name="cota2" value="{{ ($configAgente->where('tipo_config_id', App\TipoConfig::COMISSAO_2)
                                        ->first()->valor)*100 }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cota3" class="col-md-4 col-form-label text-md-right"> >= 12 & < 18 </label>

                            <div class="col-md-6">
                                <input disabled id="cota3" type="number" min="7.5" max="18" step="0.5" class="form-control" name="cota3" value="{{ ($configAgente->where('tipo_config_id', App\TipoConfig::COMISSAO_3)
                                        ->first()->valor)*100 }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cota4" class="col-md-4 col-form-label text-md-right"> >= 18 </label>

                            <div class="col-md-6">
                                <input disabled id="cota4" type="number" min="7.5" max="18" step="0.5" class="form-control" name="cota4" value="{{ ($configAgente->where('tipo_config_id', App\TipoConfig::COMISSAO_4)
                                        ->first()->valor)*100 }}" required autofocus>
                            </div>
                        </div>



                        <div class="form-group row">
                            <div class="col-4">
                                <button disabled id="btn-cancelar1" class="btn btn-warning" type="button" onclick="desativarFormulario1()">Cancelar</button>
                            </div>

                            <div class="col-4">
                                <button id="btn-editar1" class="btn" type="button" onclick="ativarFormulario1()">Editar</button>
                            </div>

                            <div class="col-4">
                                <button disabled id="btn-salvar1" class="btn btn-primary" type="submit">Salvar</button>
                            </div>
                                
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    @endif
    <!--Fim configuração agente-->

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

    function ativarFormulario1(){

        $("#oddMinima").attr('disabled', false);
        $("#oddMaxima").attr('disabled', false);
        $("#minApostado").attr('disabled', false);
        $("#maxApostado").attr('disabled', false);
        $("#minPalpites").attr('disabled', false);
        $("#maxPalpites").attr('disabled', false);

        

        $("#cota1").attr('disabled', false);
        $("#cota2").attr('disabled', false);
        $("#cota3").attr('disabled', false);
        $("#cota4").attr('disabled', false);
        $("#btn-salvar1").attr('disabled', false);
        $("#btn-editar1").attr('disabled', true);
        $("#btn-cancelar1").attr('disabled', false);
    }

    function desativarFormulario1(){
        $("#oddMinima").attr('disabled', true);
        $("#oddMaxima").attr('disabled', true);
        $("#minApostado").attr('disabled', true);
        $("#maxApostado").attr('disabled', true);
        $("#minPalpites").attr('disabled', true);
        $("#maxPalpites").attr('disabled', true);
        
        $("#cota1").attr('disabled', true);
        $("#cota2").attr('disabled', true);
        $("#cota3").attr('disabled', true);
        $("#cota4").attr('disabled', true);
        $("#btn-salvar1").attr('disabled', true);
        $("#btn-editar1").attr('disabled', false);
        $("#btn-cancelar1").attr('disabled', true);
    }

</script>
@endsection