@php
$palpites = session('palpites');
$palpiteExist = false;

if(is_array($palpites)){
	foreach ($palpites as $palpite) {
		if($palpite['evento_id']==$evento->id && $palpite['tipo_palpite']->id==$tipo_palpite_id){
			@endphp
			<button class='btn btn-info btn-sm btn-danger' data-evento='{{$evento->id}}' data-palpite="{{$tipo_palpite_id}}" onclick="enviarPalpite(this)">
				{{$evento->odds->where('tipo_palpite_id', $tipo_palpite_id)->first()->valor}}
			</button>
			@php
			$palpiteExist = true;
			break;
		}
	}
}

if(!$palpiteExist){
	$odd = $evento->odds->where('tipo_palpite_id', $tipo_palpite_id)->first();
	if(!isset($odd)){
		return;
	}
	@endphp
	<button class='btn btn-info btn-sm' data-evento='{{$evento->id}}' data-palpite="{{$tipo_palpite_id}}" onclick="enviarPalpite(this)">
		{{$evento->odds->where('tipo_palpite_id', $tipo_palpite_id)->first()->valor}}
	</button>
	@php
}

@endphp