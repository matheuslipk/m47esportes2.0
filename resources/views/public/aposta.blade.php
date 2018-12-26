@extends('componentes.pagina')

@section('titulo')
Aposta {{ $aposta->id }} - {{ $aposta->nome }}
@endsection

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-2 col-md-3"></div>
			<div class="col">
				<a style="display: none" id="botaoCompartilhar" class="btn btn-info btn-block" href="whatsapp://send?text={{ route('viewaposta', $aposta->id) }}">
					Compartilhar 
				</a>
			</div>
			<div class="col-sm-2 col-md-3"></div>
		</div>

		<div class="row">
			<div class="col-sm-2 col-md-3"></div>
			<div class="col-sm-8 col-md-6">
				<div class="card">
					<div class="card-header text-center">
					    Nº da Aposta: <b>{{$aposta->id}}</b><br>
					    Criada em: {{$aposta->data_aposta}}<br>
					    @if( isset($aposta->data_validacao) )
					    Validada em: {{$aposta->data_validacao}}<br>
					    @endif					    
					    Nome: {{$aposta->nome}}<br>
					    @if(isset($aposta->agente_id))
					    	Agente: {{$aposta->agente_id}}
					    @endif
					</div>

					<div class="card-body text-center">
						@foreach($aposta->palpites as $palpite)

						@php
							$classe = "";
							if($palpite->situacao_palpite_id==4){
								$classe="palpite-anulado";
							}elseif($palpite->situacao_palpite_id==2){
								$classe = "palpite-errou";
							}elseif($palpite->situacao_palpite_id==1){
								$classe = "palpite-acertou";
							}
						@endphp

						<div class="palpite {{$classe}}">
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
						Cota Total: {{$aposta->cotacao_total}}<br>
						Valor Apostado: R$ {{$aposta->valor_apostado}}<br>
						Possíveis ganhos: R$ {{$aposta->premiacao}}<br>
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

@section('javascript')
	<script>
		$(document).ready(function(){

			if( navigator.userAgent.match(/Android/i)
			 		|| navigator.userAgent.match(/webOS/i)
			 		|| navigator.userAgent.match(/iPhone/i)
					|| navigator.userAgent.match(/iPad/i)
					|| navigator.userAgent.match(/iPod/i)
					|| navigator.userAgent.match(/BlackBerry/i)
					|| navigator.userAgent.match(/Windows Phone/i)
			 ){
			    return $("#botaoCompartilhar").show();
			  }
		});

	</script>

@endsection