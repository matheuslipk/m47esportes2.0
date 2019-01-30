@extends('componentes.pagina')

@section('titulo')
Admin - Dashboard
@endsection

@section('content')

<canvas id="graficoApostas"></canvas>

<br>

<canvas id="graficoApostasPorAgente"></canvas>

@endsection


@section('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.js"></script>

<script type="text/javascript">

	$(document).ready(function(){
		$.get("{{ route('ajax_admin_getApostasPorSemana') }}").done(function(data){
			var arrayLabels = [];
			var arrayDados = [];
			var i = 0;

			for(indexData in data){
				switch(indexData){
					case("Monday"): arrayLabels[i] = "Segunda";
					break;

					case("Tuesday"): arrayLabels[i] = "Terça";
					break;

					case("Wednesday"): arrayLabels[i] = "Quarta";
					break;

					case("Thursday"): arrayLabels[i] = "Quinta";
					break;

					case("Friday"): arrayLabels[i] = "Sexta";
					break;

					case("Saturday"): arrayLabels[i] = "Sábado";
					break;

					case("Sunday"): arrayLabels[i] = "Domingo";
					break;

				}
				
				arrayDados[i] = data[indexData];
				i++;
			}

			construirGraficoApostasPorDia(arrayLabels, arrayDados);
		});

		$.get("{{ route('ajax_admin_getApostasPorAgente') }}").done(function(data){
			var arrayLabels = [];
			var arrayDados = [];
			var i = 0;

			for(indexData in data){
				var somaApostas = data[indexData];
				arrayLabels[i] = somaApostas.agente.nickname;
				arrayDados[i] = somaApostas.soma_apostas;

				i++;

				if(i>=4) break;
			}

			construirGraficoApostasPorAgente(arrayLabels, arrayDados);
		});

		
	});

	function construirGraficoApostasPorDia(arrayLabels, arrayDados){
		var ctx = document.getElementById("graficoApostas");
		var myChart = new Chart(ctx, {
		    type: 'bar',
		    data: {
		        labels: arrayLabels,
		        datasets: [{
		            label: 'R$',
		            data: arrayDados,
		            backgroundColor: [
		                'rgba(255, 99, 152, 0.1)',
		                'rgba(255, 99, 152, 0.1)',
		                'rgba(255, 99, 152, 0.1)',
		                'rgba(255, 99, 152, 0.1)',
		                'rgba(255, 99, 152, 0.1)',
		                'rgba(255, 99, 152, 0.1)',
		            ],
		            borderColor: [
		                'rgba(255,99,132,1)',
		                'rgba(255,99,132,1)',
		                'rgba(255,99,132,1)',
		                'rgba(255,99,132,1)',
		                'rgba(255,99,132,1)',
		                'rgba(255,99,132,1)',
		            ],
		            borderWidth: 1
		        }]
		    },
		    options: {
		    	title: {
		    		display: true,
		    		text: "Soma das apostas (Últimos 7 dias)"
		    	},
		        scales: {
		            yAxes: [{
		                ticks: {
		                    beginAtZero:true
		                }
		            }]
		        }
		    }
		});
	}

	function construirGraficoApostasPorAgente(arrayLabels, arrayDados){
		var ctx = document.getElementById("graficoApostasPorAgente");
		var myChart = new Chart(ctx, {
		    type: 'bar',
		    data: {
		        labels: arrayLabels,
		        datasets: [{
		            label: 'Apostas feitas',
		            data: arrayDados,
		            backgroundColor: [
		                'rgba(0, 255, 0, 0.1)',
		                'rgba(0, 255, 0, 0.1)',
		                'rgba(0, 255, 0, 0.1)',
		                'rgba(0, 255, 0, 0.1)',
		            ],
		            borderColor: [
		                'rgba(0, 255, 0, 1)',
		                'rgba(0, 255, 0, 1)',
		                'rgba(0, 255, 0, 1)',
		                'rgba(0, 255, 0, 1)',
		            ],
		            borderWidth: 1
		        }]
		    },
		    options: {
		    	title: {
		    		display: true,
		    		text: "Apostas por agente (Últimos 7 dias)"
		    	},
		        scales: {
		            yAxes: [{
		                ticks: {
		                    beginAtZero:true
		                }
		            }]
		        }
		    }
		});
	}

</script>
@endsection