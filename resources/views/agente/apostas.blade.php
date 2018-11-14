@extends('componentes.pagina')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-sm-6 form-group">
			<label>Data de início</label>
			<input class="form-control" type="date" name="dataInicio" id="dataInicio">
		</div>
		<div class="col-sm-6 form-group">
			<label>Data Final</label>
			<input class="form-control" type="date" name="dataFinal" id="dataFinal">
		</div>
	</div>

	<div class="row">
		<div class="col">
			<table class="table table-hover table-hover table-dark">
				<thead>
					<tr>
						<th>Aposta</th>
						<th>Valores</th>
						<th>Comissões</th>
					</tr>
				</thead>
				<tbody>
					@php
					$somaValorApostado=0;
					$somaLiquido=0;
					$somaComissao=0;
					@endphp
					@foreach($apostas as $aposta)
						@php
						$comissaoAgente = $aposta->valor_apostado * $aposta->comissao_agente;
						$valorLiquido = $aposta->valor_apostado - $comissaoAgente - $aposta->ganhou;

						$somaComissao+=$comissaoAgente;
						$somaLiquido+=$valorLiquido;
						$somaValorApostado+=$aposta->valor_apostado;
						@endphp

						<tr onclick="window.location.href='/aposta/{{$aposta->id}}'">
							<td>
								#{{$aposta->id}}<br>
								Nome: {{$aposta->nome}}
							</td>
							<td>
								Valor Apostado R$ {{$aposta->valor_apostado}}<br>
								Possível ganho R$ {{$aposta->premiacao}} 
							</td>
							<td>								
								Comissão {{$aposta->comissao_agente*100}}% <br>
								Comissão R$ {{$comissaoAgente}}<br>
								Líquido R$ {{$valorLiquido}}
							</td>
						</tr>
					@endforeach
					<tr>
						<th>Subtotal</th>
						<th>Apostas R$ {{number_format($somaValorApostado, 2)}}</th>
						<th>
							Comissão R$ {{number_format($somaComissao, 2)}}<br>
							Líquido R$ {{number_format($somaLiquido, 2)}}
						</th>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

</div>
@endsection

@section('css')
<style>
	.table>tbody{
		font-size: 10px;
	}
</style>
@endsection