@extends('componentes.pagina')

@section('content')
<div class="container-fluid">
	<form >
		<div class="row justify-content-center">	 		
			<div class="col-8 form-group">
				<label>Data dos eventos</label>
				<input class="form-control" type="date" id="data_eventos" value="{{date('Y-m-d', time())}}">
			</div>
		</div>

		<div class="row justify-content-center">
			<div class="col-8">
				<button type="button" onclick="cadastrarEventos()" class="btn btn-info btn-block">Cadastrar</button>
			</div>			
		</div>
	</form>

	<!-- Início modal -->
	<div class="modal fade" id="modalCadastroJogos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Cadastrando Jogos</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	      		<span id="quantEventos"></span><br><br>
	        	<div class="progress">
				  <div id="progressbar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
				</div>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- Fim modal -->

			

</div>
@endsection

@section('javascript')
<script>
function cadastrarEventos(){
	var dataEventos = $("#data_eventos").val();
	var pagina = 1;
	$("#modalCadastroJogos").modal('show');
	$("#progressbar").css('width', '0%').attr('aria-valuenow', 0); 
	$("#quantEventos").html("");

	$.getJSON('{{route('upcoming')}}', {
		data_eventos : dataEventos,
		page : pagina
	}).done(function(data){
		var quantEventos = parseInt(data.pager.total);
		var quantPaginas = parseInt(quantEventos/50)+1;
		var porcentagem = 0;

		$("#quantEventos").html(quantEventos + "Eventos");

		porcentagem = 1/quantPaginas * 100;

		if(porcentagem > 95){
			$("#modalCadastroJogos").modal('hide');
		}
		
		$("#progressbar").css('width', porcentagem+'%').attr('aria-valuenow', porcentagem);

		if(quantPaginas>1){
			cadastrarProxEventos(2, quantPaginas);
		}


	});

}	

function cadastrarProxEventos(pagina, quantPaginas){
	var dataEventos = $("#data_eventos").val();

	$.getJSON('{{route('upcoming')}}', {
		data_eventos : dataEventos,
		page : pagina
	}).done(function (data){
		porcentagem = pagina/quantPaginas * 100;
		$("#progressbar").css('width', porcentagem+'%').attr('aria-valuenow', porcentagem); 

		if(porcentagem > 95){
			$("#modalCadastroJogos").modal('hide');
		}

		if(pagina <= quantPaginas){
			pagina++;
			cadastrarProxEventos(pagina, quantPaginas);
		}
		return;
	});
	return;
}


</script>
@endsection

@section('css')
<style>
	.evento-id, .evento-data{
		font-size: 12px;
	}
</style>
@endsection