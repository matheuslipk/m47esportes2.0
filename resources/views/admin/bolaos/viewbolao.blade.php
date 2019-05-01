@extends('componentes.pagina')

@section('titulo')
Admin - Bolão
@endsection

@section('content')

<div class="container">

	<div class="formulario formulario-bolao">
		<div class="row justify-content-center">
			<h4>Bolão - #{{ $bolao->id }} </h4>
		</div>
		<form action="{{ route('admin_atualizarbolao', ["id" => $bolao->id]) }}" method="post">
			@method('put')
			@csrf
			<div class="row justify-content-center">
				<div class="col-12 col-sm-6">
					<label>Nome</label>
					<input class="form-control" value="{{ $bolao->nome }}" name="nome" required>
				</div>	
				<div class="col-12 col-sm-6">
					<label>Qnt de eventos</label>
					<input class="form-control" value="{{ $bolao->quant_eventos }}" type="number" name="quant_eventos" required>
				</div>				
			</div>
			
			<div class="row justify-content-center">					
				<div class="col-12 col-sm-6">
					<label>Início das apostas</label>
					<input class="form-control" value="{{ substr($bolao->data_abertura, 0, 10)."T".substr($bolao->data_abertura, 11, 5) }}" type="datetime-local" name="data_abertura" required>
				</div>
				<div class="col-12 col-sm-6">
					<label>Fim das apostas</label>
					<input class="form-control" value="{{ substr($bolao->data_fechamento, 0, 10)."T".substr($bolao->data_fechamento, 11, 5) }}" type="datetime-local" name="data_fechamento" required>
				</div>				
			</div>

			<div class="row justify-content-center">					
				<div class="col-12 col-sm-6">
					<label>Comissão Agente</label>
					<input class="form-control" value="{{ $bolao->comissao_agente }}" type="number" step="0.01" min="0" max="0.2" name="comissao_agente" required>
				</div>
				<div class="col-12 col-sm-6">
					<label>Comissão Casa</label>
					<input class="form-control" value="{{ $bolao->comissao_casa }}" type="number" step="0.01" min="0" max="0.2" name="comissao_casa" required>
				</div>				
			</div>

			<div class="row justify-content-center">
				<div class="col-12 col-sm-6">
					<label>Valor das apostas</label>
					<input class="form-control" value="{{ $bolao->valor_aposta }}" type="number" name="valor_aposta" required>
				</div>
				<div class="col-12 col-sm-6">
					<label>Status bolão</label>
					<select name="status_id" class="form-control">
						@if( $bolao->status_id === 1 )
							<option selected value="1">Válido</option>
							<option value="0">Inválido</option>
						@else
							<option value="1">Válido</option>
							<option selected value="0">Inválido</option>
						@endif
						
					</select>
				</div>			
			</div>

			<br>

			<div class="row justify-content-center">
				<div class="col-12 col-sm-6">
					<button class="btn btn-block btn-primary">Atualizar Bolão</button>
				</div>			
			</div>			

		</form>
	</div>

	<div class="formulario formulario-eventos">
		<div class="row justify-content-center">
			<h4>Adicionar eventos do bolão</h4>
		</div>

		<form>
			<div class="row justify-content-center">
				<div class="col-12 col-sm-6">
					<label>Liga</label>
					<select class="form-control" onchange="ligaChange()" id="liga">				
						<option value=""></option>		
						@foreach($ligas as $liga)
							<option value="{{ $liga->id }}">{{ $liga->nome }}</option>
						@endforeach
					</select>
				</div>

				<div class="col-12 col-sm-6">
					<label>Evento</label>
					<select class="form-control" id="evento" name="evento_id" >						
						
					</select>
				</div>
			</div>

			<br>

			<div class="row justify-content-center">
				<div class="col-6">
					<button type="button" class="btn btn-block btn-primary" onclick="addEventoBolao()">Inserir Evento</button>
				</div>
			</div>
		</form>
	</div>

	<br>

	<div class="eventos">
		<table id="tabela-eventos" class="table table-sm">
			<thead>
				<tr>
					<th>Evento</th>
					<th>Ações</th>
				</tr>
			</thead>
			<tbody>
				@foreach($bolao->eventosBolao as $eventoBolao)
				<tr style="font-size: 13px" id="tr_evento_{{$eventoBolao->id}}">
					<td >
						<span style="color: blue">{{$eventoBolao->time1->nome}}</span> x 
						<span style="color: red">{{$eventoBolao->time2->nome}}</span><br>
						{{$eventoBolao->data_evento}}
					</td>
					<td><button class="btn btn-sm btn-danger" onclick="removerEventoBolao({{$eventoBolao->id}})">Remover</button></td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>

@endsection

@section('css')
<style type="text/css">
	.formulario{
		border: 1px solid #BBB;
		margin: 10px 0px;
		padding: 20px 10px;
		border-radius: 20px;
	}
</style>
@endsection

@section('javascript')
<script type="text/javascript">
	function ligaChange(){
		$.get('{{route('admin.evento_bolaos')}}', {
			liga_id: $("#liga").val()
		}, function(eventos){
			var tbody = construirTabela(eventos);
			$("#evento").html(tbody);
		});
	}

	function construirTabela(eventos){
		var tbody = "";
		eventos.forEach(function (evento){
			tbody+="<option value='"+ evento.id +"'>";
			tbody+= evento.time1.nome + " x " +  evento.time2.nome + " - " + evento.data;
			tbody+="</option>";
		});

		return tbody;
	}

	function addEventoBolao(){
		var evento_id = $("#evento").val();

		$.post('{{ route('admin_bolaoaddeventos', ['id' => $bolao->id]) }}',{
			evento_id: evento_id
		}, function(response){
			console.log(response);
			$("#tabela-eventos > tbody").append( linhaEvento(response) );
		}).fail(function(){
			alert('Erro - O evento não foi adicionado');
		});
	}

	function removerEventoBolao(evento_id){
		$.post('{{ route('admin_bolaoremoveeventos') }}',{
			evento_id: evento_id,
			bolao_id: {{ $bolao->id }}
		}, function(response){
			if(response.sucesso === true){
				$("#tr_evento_"+evento_id).remove();
			}else{
				alert(response.msg);
			}
		}).fail(function(){
			alert('Erro - O evento não foi adicionado');
		});
	}

	function linhaEvento(evento_bolao){
		var strEvento = "<tr style='font-size: 13px' id='tr_evento_" + evento_bolao.id + "'>";
		
		strEvento+="<td>";
		strEvento+="<span style='color: blue' >"+ evento_bolao.evento.time1.nome + "</span> x <span style='color: red' >"+ evento_bolao.evento.time2.nome +"</span><br>";
		strEvento+=evento_bolao.evento.data;
		strEvento+="</td>";

		strEvento+="<td>";
		strEvento+="<button class='btn btn-sm btn-danger' onclick='removerEventoBolao(" + evento_bolao.id + ")' >Remover</button";
		strEvento+="</td>";

		strEvento+= "</tr>";

		return strEvento;

	}

</script>
@endsection
