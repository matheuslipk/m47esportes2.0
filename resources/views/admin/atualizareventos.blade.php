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
						<option value="todos">Todos</option>
						<option selected value="0">Não iniciado</option>
						<option value="1">Rolando</option>
						<option value="3">Finalizado</option>
						<option value="9">Anulado *</option>
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
					<td>Anular</td>
					<td colspan='2'>Evento</td>
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
			var status = "";
			if(eventos[index].status_evento_id == 3){
				status = "table-success";
			}
			tbody+="<tr class='"+ status +"' id='tr-evento"+ eventos[index].id +"'>";
			tbody+="<td><button class='btn btn-sm btn-danger' onclick='anularEvento("+eventos[index].id+")'>X</button>";
			tbody+="</td>";

			tbody+="<td style='padding: 0' colspan='2'>"+
				"<span class='liga'><b>"+eventos[index].liga.nome+"</b></span> <br>" +
				"<span class='evento-id'><a target='blank' href='{{ route('admin_editarevento') }}?evento_id="+ eventos[index].id + " '>" +eventos[index].id+"</a> - "+eventos[index].status_evento_name+"</span> <br>" +
				"<span class='text-primary nome-time'>"+eventos[index].time1.nome + "</span> vs " +
				"<span class='text-danger nome-time'>" + eventos[index].time2.nome + "</span><br>" +
				"<span class='evento-data'>"+eventos[index].data+"</span>"
				"</td>";
			tbody+="<td>"+
				"<button class='btn btn-sm btn-primary' onclick='atualizarEvento("+eventos[index].id+", "+eventos[index].FI_365+")'>Atualizar</button> "+
			"</td>";
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
		if(data=="Evento Finalizado"){
			$("#tr-evento"+evento_id).addClass("table-success");
		}else{
			alert(data + " - "+evento_id);
		}
	});
}

function anularEvento(evento_id){
	if(confirm("Tem certeza que deseja anular esse evento?")){
		$.get('/admin/evento/anular/'+evento_id).done(function(data){
			alert("EVENTO ANULADO");
		});
	}
	
}

</script>
@endsection

@section('css')
<style>
	.btn-sm, .nome-time, .evento-id, .evento-data, .liga{
		font-size: 12px;
	},
	th, td :{
		padding: 0
	}
</style>
@endsection