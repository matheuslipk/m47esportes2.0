@extends('componentes.pagina')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-sm-6 form-group">
			<label>Data de início</label>
			<input class="form-control" type="date" name="dataInicio" id="dataInicio" value="{{date('Y-m-d', time())}}">
		</div>
		<div class="col-sm-6 form-group">
			<label>Data Final</label>
			<input class="form-control" type="date" name="dataFinal" id="dataFinal" value="{{date('Y-m-d', time())}}">
		</div>
	</div>

	<div class="row">
		<div class="col form-group">
			<label>Agente</label>
			<select class="form-control" name="agente" id="agente">
				<option value="0">Totos</option>
				@foreach($gerente->agentes as $agente)
					<option value="{{ $agente->id }}">{{ $agente->name }}</option>
				@endforeach
			</select>
		</div>
			
	</div>

	<div class="row">
		<div class="col form-group">
			<button class="btn btn-block" onclick="atualizarTabelaApostas()">Pesquisar</button>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<table id="tabelaApostas" class="table table-hover">
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

						@if($apostasComStatus[$aposta->id]['status']==2)
							<tr class="table-danger" onclick="window.location.href='/aposta/{{$aposta->id}}'">
						@elseif($apostasComStatus[$aposta->id]['status']==3)
							<tr class="table-warning" onclick="window.location.href='/aposta/{{$aposta->id}}'">
						@elseif($apostasComStatus[$aposta->id]['status']==1)
							<tr class="table-success" onclick="window.location.href='/aposta/{{$aposta->id}}'">
						@endif
							<td>
								#{{$aposta->id}}<br>
								Nome: {{$aposta->nome}}<br>
								Data: {{$aposta->data_aposta}}<br>
								Agente: {{ $aposta->agente->nickname }}
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


@section('javascript')
<script>
	function atualizarTabelaApostas(){
		var dataInicio = $('#dataInicio').val();
		var dataFinal = $('#dataFinal').val();
		var agente = $("#agente").val();

		$.getJSON('/gerente/apostasJSON',{
			data_inicio : dataInicio,
			data_final : dataFinal,
			agente : agente
		}).done(function(data){
			$("#tabelaApostas>tbody").html(construirTabeleApostas(data));
		});
	}

	function construirTabeleApostas(data){
		var apostas = data.apostas;
		var apostasComStatus = data.apostasComStatus;

		var tabela="";
		var somaValorApostado=0;
		var somaLiquido=0;
		var somaComissao=0;

		for(index in apostas){
			var comissaoAgente = apostas[index].valor_apostado * apostas[index].comissao_agente;
			var valorLiquido = apostas[index].valor_apostado - comissaoAgente - apostas[index].ganhou;

			somaValorApostado += parseInt(apostas[index].valor_apostado);
			somaLiquido += valorLiquido;
			somaComissao += comissaoAgente;


			var classeAposta = "";

			if(apostasComStatus[ apostas[index].id ].status == 2 ){
				classeAposta = "table-danger";
			}else if( apostasComStatus[ apostas[index].id ].status == 3 ){
				classeAposta = "table-warning";
			}else if( apostasComStatus[ apostas[index].id ].status == 1 ){
				classeAposta = "table-success";
			}

			tabela+="<tr class='"+ classeAposta +"' onclick=\"window.location.href='/aposta/"+apostas[index].id+"' \">";

			tabela+="<td>#"+
				apostas[index].id+"<br>"+
				"Nome: "+apostas[index].nome+"<br>"+
				"Agente: "+apostas[index].agente.nickname+"<br>"+
				"Data: "+apostas[index].data_aposta+
				"</td>";

			tabela+="<td>"+
				"Valor Apostado R$ "+apostas[index].valor_apostado+"<br>"+
				"Possível ganho R$ "+apostas[index].premiacao+
				"</td>";

			tabela+="<td>"+
				"Comissão "+(apostas[index].comissao_agente)*100+"%<br>"+
				"Comissão R$ "+comissaoAgente.toFixed(2)+"<br>"+
				"Líquido R$ "+valorLiquido.toFixed(2)+"<br>"+
				"</td>";			

			tabela+='</tr>';
		}
		tabela+='<tr>';
		tabela+="<th>Subtotal</th>";
		tabela+="<th>Apostas R$ "+somaValorApostado.toFixed(2)+"</th>";

		tabela+="<th>"+
				"Comissão R$ "+somaComissao.toFixed(2)+"<br>"+
				"Líquido R$ "+somaLiquido.toFixed(2)+
				"</th>";

		tabela+='</tr>';

		return tabela;

	}
</script>
@endsection

@section('css')
<style>
	.table>tbody{
		font-size: 10px;
	}
</style>
@endsection