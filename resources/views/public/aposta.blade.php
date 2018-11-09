@extends('componentes.pagina')

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-2 col-md-3"></div>
			<div class="col-sm-8 col-md-6">
				<div class="card">
					<div class="card-header text-center">
					    Aposta: {{$aposta->id}}<br>
					    Criada em: {{$aposta->data_aposta}}<br>
					    Nome: {{$aposta->nome}}
					</div>

					<div class="card-body text-center">
						@foreach($aposta->palpites as $palpite)
						<div class="palpite">
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

					<div class="card-footer">
						teste
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
			border-bottom: 1px solid #ddd;
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