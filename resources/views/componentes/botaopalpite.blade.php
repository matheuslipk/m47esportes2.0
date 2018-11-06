<button class='btn btn-info btn-sm' onclick="enviarPalpite({{$evento->id}}, {{$tipo_palpite_id}})">
	{{$evento->odd($evento->id, $tipo_palpite_id)}}
</button>