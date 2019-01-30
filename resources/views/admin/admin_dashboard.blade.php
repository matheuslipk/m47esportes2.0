@extends('componentes.pagina')



@section('content')

<canvas id="graficoApostas"></canvas>

<script type="text/javascript">

			

</script>


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
				arrayLabels[i] = indexData;
				arrayDados[i] = data[indexData];
				i++;
			}

			construirGraficoApostasPorGerente(arrayLabels, arrayDados);
		});
		
	});

	function construirGraficoApostasPorGerente(arrayLabels, arrayDados){
		var ctx = document.getElementById("graficoApostas");
		var myChart = new Chart(ctx, {
		    type: 'bar',
		    data: {
		        labels: arrayLabels,
		        datasets: [{
		            label: 'Apostas',
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
		    		text: "Resumo de Apostas por dia"
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