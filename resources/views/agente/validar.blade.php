@extends('componentes.pagina')

@section('content')
	<div class="container">
		<form method="post" action="{{ route('agentevalidar') }}">
			@csrf
			<div class="row">
				<div class="col"></div>
				<div class="col-sm-8 form-group">
					<label>Número da aposta</label>
					<input placeholder="Número da aposta" class="form-control" type="number" name="aposta_id" id="apostaId">
				</div>		
				<div class="col"></div>						
			</div>
			<div class="row">
				<div class="col"></div>
				<div class="col-sm-8 form-group">
					<button onclick="exibirModal()" type="button" class="btn btn-info form-control">Visualizar</button>
				</div>
				<div class="col"></div>
			</div>

			<div class="modal fade" id="modalValidarAposta" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			  <div class="modal-dialog modal-dialog-centered" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="modal-titulo">Título</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body" id="modal-body">

			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
			        <button id="botao-validar" type="submit" class="btn btn-primary">Validar</button>
			      </div>
			    </div>
			  </div>
			</div>

		</form>
	</div>


@endsection

@section('javascript')
<script>
	function exibirModal(){
		var apostaId = $("#apostaId").val();
		$.get("/agente/apostaJSON/"+apostaId).done(function (data){
			if(data.sucesso===true){
				if(data.validacao_disponivel===true){					
					$('#modal-titulo').html("Aposta: "+data.aposta.id);
					$('#modal-body').html(construirAposta(data.aposta));
					$("#modalValidarAposta").modal('show');
				}else{
					alert(data.msg);
				}	
			}else{
				alert(data.msg);
			}
			
		});
		
	}
	function construirAposta(ap){
		var aposta="";
		aposta+="<div class='card'>";//Inicio Card

		aposta+="<div class='card-header text-center'>";//Inicio Cabecalho
		aposta+="Nº da Aposta: <b>"+ap.id+"</b><br>";
		aposta+="Criada em: "+ap.data_aposta+"<br>";
		aposta+="Nome: "+ap.nome;
		aposta+="</div>";//Fim cabeçalho

		aposta+="<div class='card-body text-center'>";//Inicio corpo
		for(index in ap.palpites){
			aposta+="<div class='palpite'>";//Inicio Palpite

			aposta+="<div class='evento'>";//Inicio Evento
			aposta+="<span class='text-primary'>"+ap.palpites[index].evento.time1.nome+"</span> vs ";
			aposta+="<span class='text-danger'>"+ap.palpites[index].evento.time2.nome+"</span><br>";
			aposta+="<span class='data-evento'>"+ap.data_aposta+"</span>";
			aposta+="</div>";//Fim Evento

			aposta+="<div class='desc-palpite'>";//Inicio desc palpite 
			aposta+="<span>"+ap.palpites[index].tipo_palpite.cat_palpite.nome+"</span><br>";
			aposta+="<span><b>"+ap.palpites[index].tipo_palpite.nome+"</b> : "+ap.palpites[index].cotacao+"</span><br>";
			aposta+="</div>";//Fim desc palpite 

			aposta+="</div>";//Fim Palpite
		}
		aposta+="</div>";//Fim do Corpo

		aposta+="<div class='card-footer text-center'>";//Inicio Footer
		aposta+="Cota Total: "+ap.cotacao_total+"<br>";
		aposta+="Valor Apostado: R$ "+ap.valor_apostado+"<br>";
		aposta+="Possíveis ganhos: R$ "+ap.premiacao+"<br>";
		aposta+="</div>";//Fim Footer

		aposta+="</div>";//Fim card
		return aposta;
	}
</script>
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