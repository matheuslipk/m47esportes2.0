@extends('componentes.pagina')

@section('content')
<div class="container-fluid">
	<form >
		<div class="row">			
			<div class="col">
				<div class="form-group">
					<label>Data inínio</label>
					<input class="form-control" type="datetime-local" id="dataInicio" value="{{date('Y-m-d', time())}}T00:00">
				</div>
			</div>
			<div class="col">
				<div class="form-group">
					<label>Data Fim</label>
					<input class="form-control" type="datetime-local" id="dataFim" value="{{date('Y-m-d', time())}}T12:00">
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col">
				<div class="form-group">
					<label>Status do Jogo</label>
					<select class="form-control" id="statusEvento">
						<option selected value="0">Não iniciado</option>
						<option value="1">Rolando</option>
						<option value="3">Finalizado</option>
					</select> 
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<button type="button" onclick="atualizarListaEventos()" class="btn btn-info btn-block">Pesquisar</button>
			</div>			
		</div>
	</form>

	<div>
		<table class="table text-center" border="1"  id="tabelaEventos">
			<thead>
				<tr>
					<td>Evento</td>
					<td>Ações</td>
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
function atualizarListaEventos(){
	var dataInicio = $("#dataInicio").val();
	var dataFim = $("#dataFim").val();
	var statusEvento = $("#statusEvento").val();

	$.get('{{route('getEventosJSONAdmin')}}', {
		dataInicio : dataInicio,
		dataFim : dataFim,
		statusEvento : statusEvento
	}).done(function(eventos){
		var tbody = "";
		for(index in eventos){
			tbody+='<tr>';
			tbody+="<td>"+
				"<span class='evento-id'>"+eventos[index].id+" - "+eventos[index].status_evento_id+"</span> <br>" +
				"<span class='text-primary'>"+eventos[index].time1.nome + "</span> vs " +
				"<span class='text-danger'>" + eventos[index].time2.nome + "</span>" +
				"</td>";
			tbody+="<td><button class='btn btn-sm' onclick='atualizarEvento("+eventos[index].id+", "+eventos[index].FI_365+")'>Atualizar</button></td>";
			tbody+='</tr>';
		}
		$('#tabelaEventos>tbody').html(tbody);

		
	});

}	

function atualizarEvento(evento_id, fi_365){
	$.get('/admin/evento/atualizarNaApi', {
		event_id : evento_id,
		FI : fi_365
	}).done(function(data){
		alert(data);
	});
}
</script>
@endsection

@section('css')
<style>
	.evento-id{
		font-size: 12px;
	}
</style>
@endsection