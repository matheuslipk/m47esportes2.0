@extends('componentes.pagina')

@php
$scoreCasa = null;
$scoreFora = null;
$scoreCasa1t = null;
$scoreFora1t = null;
$scoreCasa2t = null;
$scoreFora2t = null;

if(isset($evento->scores)){
	$scoreCasa = $evento->scores->score_t1;
	$scoreFora = $evento->scores->score_t2;
}

if(isset($evento->scores_t1)){
	$scoreCasa1t = $evento->scores_t1->score_t1;
	$scoreFora1t = $evento->scores_t1->score_t2;
}

if(isset($evento->scores_t2)){
	$scoreCasa2t = $evento->scores_t2->score_t1;
	$scoreFora2t = $evento->scores_t2->score_t2;
}

@endphp

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<h3>
			<span style="color: blue">{{$evento->time1->nome}}</span> x <span style="color: red">{{$evento->time2->nome}}</span>
		</h3>
	</div>
	<form action="{{ route('admin_editarevento') }}" method="post">
		@csrf
		<input type="hidden" name="evento_id" value="{{$evento->id}}">
		<table class="table">
			<tbody>				
				<tr>
					<td>
						<input class="form-control" type="number" name="gols_casa_1t" value="{{ $scoreCasa1t }}">
					</td>
					<td>
						<input class="form-control" type="number" name="gols_fora_1t" value="{{ $scoreCasa1t }}">
					</td>
				</tr>
				<tr>
					<td>
						<input class="form-control" type="number" name="gols_casa_2t" value="{{ $scoreCasa2t }}">
					</td>
					<td>
						<input class="form-control" type="number" name="gols_fora_2t" value="{{ $scoreCasa2t }}">
					</td>
				</tr>
				<tr>
					<td>
						<input class="form-control" type="number" name="gols_casa" value="{{$scoreCasa}}">
					</td>
					<td>
						<input class="form-control" type="number" name="gols_fora" value="{{ $scoreFora }}">
					</td>
				</tr>
			</tbody>
		</table>
	

		<div class="row justify-content-center">
			<div class="col-sm-6">
				<button class="btn btn-primary btn-block" type="submit">Atualizar</button>
			</div>
		</div>

	</form>

</div>
@endsection

@section('javascript')
<script>

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