@extends('componentes.pagina')

@section('content')

<div class="container-fluid">
@foreach($ligas as $liga)

@if(count($liga->eventos)==0)
	@continue
@endif

    <div id="accordion{{$liga->id}}">
        <div class="card">
            <div class="card-header bg-secondary text-white" data-toggle="collapse" data-target='#collapseOne{{$liga->id}}'>
                <div>{{$liga->nome}}</div>
            </div>

            <!--Parte dos eventos-->            
            <div id="collapseOne{{$liga->id}}" class="collapse show" data-parent="#accordion{{$liga->id}}">
                <div class="card-body">
                	<table border="0" class="center table table-sm">
                		<thead>
					        <tr>
					            <th>Equipes</th>
					            <th colspan="2">+opções</th>
					        </tr>
					    </thead>
					    <tbody>
	                    	@foreach($liga->eventos as $evento)						    
						        <tr>
						            <td>
						            	<span class="text-primary">{{ $evento->time1->nome }}</span> x
						            	<span class="text-danger">{{ $evento->time2->nome }}</span> <br>
						            	<span>{{ $evento->data }}</span> 
						            </td>
						            <td>
						            	@if($evento->quantOdds == 0)
							                <button class="btn btn-primary btn-sm" onclick="atualizar_odds({{$evento->id}})">
							                    <span class="badge badge-light">Atualizar Odds</span>
							                </button>

						                @else
							                <button class="btn btn-danger btn-sm" onclick="remover_odds({{$evento->id}})">
							                    <span class="badge badge-light">Remover Odds</span>
							                </button>
						                @endif
						            </td>
						        </tr>						    							
	                    	@endforeach
	                    </tbody>
                	</table>
                </div>
            </div>            
            <!--Fim Parte dos eventos-->

        </div>
    </div>        
    
@endforeach
</div>
@endsection



@section('css')
<style type="text/css">
	body{
        background: rgba(75, 200, 125, 0.2);
    }   
    tr, form{
        font-size: 12px;
    }
    .cat_palpite{
        background: #222;
        font-size: 19px;
        color: #fff;
    }
    .card-campeonato{
        background: rgba(150, 150, 150, 0.3);
    }
</style>
@endsection

@section('javascript')
<script type="text/javascript">
	function atualizar_odds(evento_id){
		$.get('/api365/prematch',{
			event_id : evento_id
		});
	}

	function remover_odds(evento_id){
		$.post('/admin/odds/remover',{
			evento_id : evento_id
		});
	}
</script>
@endsection



						 