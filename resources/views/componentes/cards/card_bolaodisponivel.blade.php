<form method="post" action="{{ route('agente.fazerbolao') }}">
	@csrf
	<input type="hidden" name="bolao_id" value="{{ $bolao->id }}">
	<div class="card" style="width: 22rem;">
	  	<div class="card-body">
	    	<h5 class="card-title">{{ $bolao->nome }}</h5>
	    	<p class="card-text">
	    		Comissão Agente: {{ $bolao->comissao_agente*100 }}%<br>
	    		Comissão Casa: {{ $bolao->comissao_casa*100 }}%
	    	</p>
	  	</div>

  		<table border="0">
  			<thead style="text-align: center;">
  				<tr>
  					<th>Casa</th>
  					<th>Empate</th>
  					<th>Fora</th>
  				</tr>
  			</thead>

  			<tbody>
  				@foreach($bolao->eventosBolao as $evento)
					<tr>
			  			<td>
			  				<input id="{{$bolao->id}}_{{$evento->id}}_1" onchange="selecionarCampo(this)" type="radio" name="eventos[{{$evento->id}}]" value="1" required>
			  				<label for="{{$bolao->id}}_{{$evento->id}}_1">{{ $evento->time1->nome }}</label>
			  			</td>
			  			<td>
			  				<input id="{{$bolao->id}}_{{$evento->id}}_2" onchange="selecionarCampo(this)" type="radio" name="eventos[{{$evento->id}}]" value="2" required>
			  				<label for="{{$bolao->id}}_{{$evento->id}}_2">X</label>
			  			</td>
			  			<td>
			  				<label for="{{$bolao->id}}_{{$evento->id}}_3">{{ $evento->time2->nome }}</label>
			  				<input id="{{$bolao->id}}_{{$evento->id}}_3" onchange="selecionarCampo(this)" type="radio" name="eventos[{{$evento->id}}]" value="3" required>
			  			</td>
			  		</tr>
			  	@endforeach
  			</tbody>
  		</table>
				  	
	  	<div class="card-body">
	  		<input class="form-control" name="nome_cliente" maxlength="15" placeholder="Cliente"><br>
	  		<button class="btn btn-block btn-success" type="submit">Fazer Aposta (R$ {{ $bolao->valor_aposta }}) </button>
	  	</div>
	</div>
</form>
	