@extends('componentes.pagina')

@section('content')
<div class="container-fluid">
	<form >
		<div class="row">	 		
			<div class="col form-group">
				<label>Data dos eventos</label>
				<input class="form-control" type="date" id="data_eventos" value="{{date('Y-m-d', time())}}">
			</div>
			<div class="col form-group">
				<label>Página</label>
				<select class="form-control" name="pagina" id="pagina">
					@for($i=1; $i<=20; $i++)
						<option>{{$i}}</option>
					@endfor
				</select>
			</div>
		</div>

		<div class="row">
			<div class="col">
				<button type="button" onclick="cadastrarEventos()" class="btn btn-info btn-block">Pesquisar</button>
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
function cadastrarEventos(){
	var dataEventos = $("#data_eventos").val();
	var pagina = $("#pagina").val();

	$.getJSON('{{route('upcoming')}}', {
		data_eventos : dataEventos,
		page : pagina
	}).done(function(data){
		alert("Total de eventos: "+data.pager.total);
	});

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