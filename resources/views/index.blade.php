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
                        <tbody>
                            @foreach($liga->eventos as $evento)
                                @php
                                    $quantOdds = $evento->quantOdds($evento->id);
                                    if($quantOdds==0){
                                        continue;
                                    }
                                @endphp

                                <tr>
                                    <td>
                                        <span class="text-primary">{{ $evento->time1->nome }}</span> x
                                        <span class="text-danger">{{ $evento->time2->nome }}</span> <br>
                                        <span>{{ $evento->data }}</span> 
                                    </td>
                                    <td>@include('componentes.botaopalpite', ['evento'=>$evento, 'tipo_palpite_id'=>1])</td>
                                    <td>@include('componentes.botaopalpite', ['evento'=>$evento, 'tipo_palpite_id'=>2])</td>
                                    <td>@include('componentes.botaopalpite', ['evento'=>$evento, 'tipo_palpite_id'=>3])</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" onclick="exibirModalOdds({{$evento}})">
                                            <span class="badge badge-light">+{{$quantOdds}}</span>
                                        </button>
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


    <!--Modal Odds-->
    <div class="modal fade" id="modal-odds" tabindex="-1" role="dialog" aria-labelledby="modal-odds" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="titulo-modal"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" >
                <table class="table table-sm">
                    <tbody id="modal-body">
                        
                    </tbody>
                </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!--Modal Palpites-->
    <div class="modal fade" id="modal-palpites" tabindex="-1" role="dialog" aria-labelledby="modal-palpites" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="titulo-modal-palpites">Seus palpites</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" >
                <table class="table table-sm">
                    <tbody id="modal-palpites-body">
                        
                    </tbody>
                </table>
                <form action="{{route('fazerAposta')}}" method="post" id="form-fazerAposta">
                    @csrf
                    <div class="form-group">
                        <label for="valorAposta">Valor da Aposta</label>
                        <input class="form-control" placeholder="Valor da aposta" type="number" name="valorAposta" id="valorAposta">
                    </div>
                    <div class="form-group">
                        <label for="nomeAposta">Nome da aposta</label>
                        <input class="form-control" placeholder="Digite um nome para sua aposta" name="nomeAposta" id="nomeAposta">
                    </div>
                    <button class="btn btn-sm btn-warning">Fazer Aposta</button>
                </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
          </div>
        </div>
      </div>
    </div>    

@endforeach
<div class="flutuante">
    <button class="btn btn-success" onclick="exibirModalPalpites()">Palpites</button>
</div>
</div>
@endsection



@section('css')
<style type="text/css">
    tr, form{
        font-size: 12px;
    }
    body{
        background: #666;
    }
    .cat_palpite{
        background: #aaa;
        font-size: 19px;
    }
    .flutuante{
        position: fixed;
        right: 30px;
        bottom: 23px;
        z-index: 100;
    }
    .flutuante>button{
        border-radius: 20px;
        font-size: 18px;
    }
    .btn-remove{
        margin: 0px 0px 0px 10px;
    }
</style>
@endsection

@section('javascript')
    @include('js.minhasFuncoes')
@endsection