@extends('componentes.pagina')

@section('content')

<div class="container-fluid">
@foreach($ligas as $liga)

@if(count($liga->eventos)==0)
	@continue
@endif

    <div id="accordion{{$liga->id}}">
        <div class="card">
            <div class="card-header bg-secondary" data-toggle="collapse" data-target='#collapseOne{{$liga->id}}'>
                <div>{{$liga->nome}}</div>
            </div>

            <!--Parte dos eventos-->            
            <div id="collapseOne{{$liga->id}}" class="collapse show" data-parent="#accordion{{$liga->id}}">
                <div class="card-body">
                	<table border="0" class="center table table-sm">
                		<thead>
					        <tr>
					            <th>Equipes</th>
					            <th>Casa</th>
					            <th>Empate</th>
					            <th>Fora</th>
					            <th>+opções</th>
					        </tr>
					    </thead>
					    
	                    	@foreach($liga->eventos as $evento)							   
						    <tbody>
						        <tr>
						            <td>
						            	<span class="text-primary">{{ $evento->time1->nome }}</span> x
						            	<span class="text-danger">{{ $evento->time2->nome }}</span> <br>
						            	<span>{{ $evento->data }}</span> 
						            </td>
						            <td><button class="btn btn-sm">2.00</button></td>
						            <td><button class="btn btn-sm">3.00</button></td>
						            <td><button class="btn btn-sm">2.00</button></td>
						            <td>
						                <button class="btn btn-primary btn-sm" data-toggle='modal' data-target='#myModal'>
						                    <span class="badge badge-light">Atualizar Odds</span>
						                </button>
						            </td>
						        </tr>                                
						    </tbody>							
	                    	@endforeach

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
		background: #666;
	}
	table{
		font-size: 14px;
	}
</style>
@endsection





						 