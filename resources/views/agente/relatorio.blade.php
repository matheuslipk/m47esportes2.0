@extends('componentes.pagina')

@section('content')

<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<br>
			<div class="card">
				<div class="card-header">Relatório</div>

				<div class="card-body">
					<form >
						@csrf

						<div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Início</label>

                            <div class="col-md-6">
                                <input id="data_inicio" class="form-control" type="date" name="data_inicio" value="{{ date("Y-m-d", time() ) }}" >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Fim</label>

                            <div class="col-md-6">
                                <input id="data_fim" class="form-control" type="date" name="data_fim" value="{{ date("Y-m-d", time() ) }}" >
                            </div>
                        </div>
                       
                        <div class="form-group row">
                        	<div class="col-12">
                        		<button type="button" class="btn btn-primary btn-block" onclick="getRelatorio2()">Ver relatório</button>
                        	</div>	                            
                        </div>

					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="row justify-content-center">
		<table class="table col-sm-6" id="tabela_relatorio">
			<thead>
				<tr>
					<th colspan="2">Relatório</th>
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
	</div>
</div>

@endsection

@section('javascript')
<script>
	function getRelatorio(){
		var dataInicio = $('#data_inicio').val();
		var dataFim = $('#data_fim').val();

		$.post('{{ route('relatorio_agente') }}', {
			data_inicio: dataInicio,
			data_fim: dataFim

		}).done(function (data){
			var valorAposta=0;
			var comissao=0;
			var premio=0;

			var somaApostas=0;
			var somaComissao=0;
			var somaPremios=0;

			for(index in data.apostas){
				valorAposta = parseFloat(data.apostas[index].valor_apostado);
				comissao = parseFloat(data.apostas[index].comissao_agente);

				//Se aposta for premiada
				if(data.apostasComStatus[ data.apostas[ index ].id ].status == 1){
					premio = parseFloat(data.apostas[index].premiacao);
					somaPremios -= premio;					
				}

				somaApostas += valorAposta;
				somaComissao -= valorAposta * comissao;

			}
			var tr = "<tr>";
			tr += "<td>R$"+ somaApostas.toFixed(2) +"</td>";
			tr += "<td>R$"+ somaComissao.toFixed(2) +"</td>";
			tr += "<td>R$"+ somaPremios.toFixed(2) +"</td>";
			tr += "<td>R$"+ (somaApostas + somaComissao + somaPremios).toFixed(2) +"</td>";
			tr += "</tr>";

			$("#tabela_relatorio>tbody").html(tr);
		});
	}

	function getRelatorio2(){
		var dataInicio = $('#data_inicio').val();
		var dataFim = $('#data_fim').val();

		$.post('{{ route('relatorio_agente') }}', {
			data_inicio: dataInicio,
			data_fim: dataFim

		}).done(function (data){
			var valorAposta=0;
			var comissao=0;
			var premio=0;

			var somaApostas=0;
			var somaComissao=0;
			var somaPremios=0;

			for(index in data.apostas){
				valorAposta = parseFloat(data.apostas[index].valor_apostado);
				comissao = parseFloat(data.apostas[index].comissao_agente);

				//Se aposta for premiada
				if(data.apostasComStatus[ data.apostas[ index ].id ].status == 1){
					premio = parseFloat(data.apostas[index].premiacao);
					somaPremios -= premio;					
				}

				somaApostas += valorAposta;
				somaComissao -= valorAposta * comissao;

			}
			var total = somaApostas+somaComissao+somaPremios;
			var corTexto = "";
			if(total>=0){
				corTexto = "text-success";
			} else{
				corTexto = "text-danger";
			}


			var tr = "<tr>";
			tr += "<td>Arrecadado</td>";
			tr += "<td>R$"+ somaApostas.toFixed(2) +"</td>";
			tr += "</tr>";

			tr += "<tr><td>Comissão</td>";
			tr += "<td>R$"+ somaComissao.toFixed(2) +"</td></tr>";

			tr += "<tr><td>Prêmios</td>";
			tr += "<td>R$"+ somaPremios.toFixed(2) +"</td></tr>";

			tr += "<tr><td>Total</td>";
			tr += "<td class='" + corTexto + "'><b>R$"+ (somaApostas+somaComissao+somaPremios).toFixed(2) +"</b></td></tr>";

			$("#tabela_relatorio>tbody").html(tr);
		});
	}


</script>
@endsection