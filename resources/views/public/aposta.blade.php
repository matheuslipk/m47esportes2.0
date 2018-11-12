@extends('componentes.pagina')

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-2 col-md-3"></div>
			<div class="col-sm-8 col-md-6">
				<div class="card">
					<div class="card-header text-center">
					    Nº da Aposta: <b>{{$aposta->id}}</b><br>
					    Criada em: {{$aposta->data_aposta}}<br>
					    Nome: {{$aposta->nome}}<br>
					    Cota Total: {{$aposta->cotacao_total}}<br>
						Valor Apostado: R$ {{$aposta->valor_apostado}}<br>
						Possíveis ganhos: R$ {{$aposta->premiacao}}
					</div>

					<div class="card-body text-center">
						@foreach($aposta->palpites as $palpite)
							@if($palpite->situacao_palpite_id==3)
							<div class="palpite">
							@elseif($palpite->situacao_palpite_id==2)
							<div class="palpite palpite-errou">
							@elseif($palpite->situacao_palpite_id==1)
							<div class="palpite palpite-acertou">	
							@endif
								<div class="evento">
								<span class="evento-id">Evento: {{$palpite->evento->id}}</span><br>
								<span class="text-primary">{{$palpite->evento->time1->nome}}</span> vs 
								<span class="text-danger">{{$palpite->evento->time2->nome}}</span><br>
								<span class="data-evento">{{$palpite->evento->data}}</span>
							</div>	
							<div class="desc-palpite">
								<span>{{$palpite->tipo_palpite->cat_palpite->nome}}</span><br>
								<span><b>{{$palpite->tipo_palpite->nome}}</b> : {{$palpite->cotacao}}</span><br>
								<span class="status-evento">({{$palpite->situacao_palpite->nome}})</span>
							</div>						
						</div>
						@endforeach
					</div>

					<div class="card-footer text-center">
						Aqui ficará algumas regras do site
					</div>
				</div>
				<div class="col-sm-2 col-md-3"></div>
			</div>
				
		</div>
	</div>
@endsection

@section('css')
	<style>		
		.palpite{
			border-radius: 20px;
			border: 1px solid #ddd;
		}
		.card{
			font-size: 13px;
		}
		.data-evento, .evento-id, .status-evento{
			font-size: 10px;
		}
		.palpite-errou{
			background: #fbb;
		}
		.palpite-acertou{
			background: #bfb;
		}
		.palpite-anulado{
			background: #ffa;
		}
	</style>
@endsection