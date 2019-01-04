@extends('componentes.pagina')

@section('content')

<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<br>
			<div class="card">
				<div class="card-header">Relatório</div>

				<div class="card-body">
					<form method="post">
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
                        		<button type="button" class="btn btn-primary btn-block" onclick="getRelatorio()">Ver relatório</button>
                        	</div>	                            
                        </div>

					</form>
				</div>
			</div>
		</div>
	</div>

	<div>
		<table class="table table-sm" id="tabela_relatorio">
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

		$.post('{{ route('relatorio_admin') }}', {
			data_inicio: dataInicio,
			data_fim: dataFim

		}).done(function (data){
			construirTabelaRelatorioAdmin(data);
		
		});
	}

	function construirTabelaRelatorioAdmin(data){
		var somaApostas=0;
		var somaComissao=0;
		var somaPremios=0;

		
		var tr = "";

		for(idxGerente in data.gerentes){
			var gerente = data.gerentes[idxGerente];
			var somaApostasGerente=0;
			var somaComissaoGerente=0;
			var somaPremiosGerente=0;

			tr += "<tr>";
			tr += "<th colspan='4'><b>GERENTE: "+ gerente.name +"</b></th>";
			tr += "</tr>";

			for(idxAgente in gerente.agentes){
				var agente = gerente.agentes[idxAgente];
				var liqAgente = agente.soma_apostas - agente.comissao - agente.premiacao;
				somaApostasGerente += agente.soma_apostas;
				somaComissaoGerente += agente.comissao;
				somaPremiosGerente += agente.premiacao;

				tr += "<tr>";
				tr += "<td>"+ agente.name +"</td>";
				tr += "<td>"+ (agente.soma_apostas).toFixed(2) +"</td>";
				tr += "<td>"+ (agente.comissao).toFixed(2) +"</td>";
				tr += "<td>"+ (agente.premiacao).toFixed(2) +"</td>";
				tr += "<td>"+ (liqAgente).toFixed(2) +"</td>";
				tr += "</tr>";
				console.log(agente); 
			}
			somaApostas += somaApostasGerente;
			somaComissao += somaComissaoGerente;
			somaPremios += somaPremiosGerente;

			var liqGerente = somaApostasGerente - somaComissaoGerente - somaPremiosGerente;
			tr += "<tr>";
			tr += "<th><b>Subtotal</b></th>";
			tr += "<th>"+ (somaApostasGerente).toFixed(2) +"</th>";
			tr += "<th>"+ (somaComissaoGerente).toFixed(2) +"</th>";
			tr += "<th>"+ (somaPremiosGerente).toFixed(2) +"</th>";
			tr += "<th>"+ (liqGerente).toFixed(2) +"</th>";
			tr += "</tr>";

		}
		var liq =somaApostas - somaComissao - somaPremios;
		tr += "<tr>";
		tr += "<th><b>Total Geral: </b></th>";
		tr += "<th>"+ (somaApostas).toFixed(2) +"</th>";
		tr += "<th>"+ (somaComissao).toFixed(2) +"</th>";
		tr += "<th>"+ (somaPremios).toFixed(2) +"</th>";
		tr += "<th>"+ (liq).toFixed(2) +"</th>";
		tr += "</tr>";

		$("#tabela_relatorio>tbody").html(tr);
	}


</script>
@endsection