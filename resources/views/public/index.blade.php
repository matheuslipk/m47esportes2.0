@extends('componentes.pagina')

@section('content')

<div class="container-fluid">
    <a class="btn btn-primary" href="/">Todos os jogos</a>
    <a class="btn btn-primary" href="/?data=hoje">Jogos de hoje</a>
@foreach($ligas as $liga)

@if(count($liga->eventos)==0)
    @continue
@endif

    <div id="accordion{{$liga->id}}" class="">
        <div class="card">
            <div class="card-header bg-dark text-white" data-toggle="collapse" data-target='#collapseOne{{$liga->id}}'>
                <div>{{$liga->nome}}</div>
            </div>

            <!--Parte dos eventos-->            
            <div id="collapseOne{{$liga->id}}" class="collapse show card-campeonato" data-parent="#accordion{{$liga->id}}">
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
                                $quantOdds = $evento->quantOdds;
                                @endphp
                                @if($quantOdds == 0)
                                    @continue;
                                @endif

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


        

@endforeach

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
          <div class="modal-body card-campeonato" >
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#tempo-completo">90 Min</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tempo1">1º Tempo</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tempo2">2º Tempo</a></li>
                </ul>
                <div class="tab-content">
                    <div id="tempo-completo" class="tab-pane fade in active show">
                        <table class="table table-sm">
                            <tbody id="modal-body">
                                
                            </tbody>
                        </table>
                    </div>
                        
                    <div  id="tempo1" class="tab-pane fade in">
                        <table class="table table-sm">
                            <tbody id="modal-body1">
                                
                            </tbody>
                        </table>
                    </div>
                        
                    <div id="tempo2" class="tab-pane fade in">
                        <table class="table table-sm">
                            <tbody id="modal-body2">
                                
                            </tbody>
                        </table>
                    </div>
                        
                </div>
                    
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
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
                        <input  min="2"  class="form-control" placeholder="Valor da aposta" type="number" name="valorAposta" id="valorAposta" onkeyup ="atualizarPossivelGanho()", onclick="atualizarPossivelGanho()">
                    </div>
                    <div class="form-group">
                        <label for="nomeAposta">Nome da aposta</label>
                        <input class="form-control" placeholder="Digite um nome para sua aposta" name="nomeAposta" id="nomeAposta">
                    </div>

                    <div class="form-group">
                        <input type="checkbox" name="regrasOk" id='regrasOk' onchange="verificarRegrasOk()">
                        <label for="regrasOk" class="label"><b> Eu li e estou de acordo com todas as <a target="_blank" href="{{ route('regras') }}">regras do site.</a> </b></label>

                        <button class="btn btn-sm btn-primary btn-block" id="btn-fazerAposta">Fazer Aposta</button>
                        <button type="button" class="btn btn-sm btn-secondary btn-block" data-dismiss="modal">Voltar</button>
                    </div>
                    
                </form>
          </div>
          <div class="modal-footer">
            <!--Colocar algumas informações das apostas-->
          </div>
        </div>
      </div>
    </div>
    <!--Fim Modal Palpites-->

<div class="flutuante">
    <button class="btn btn-success" onclick="exibirModalPalpites()">Palpites</button>
</div>
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
    .card-campeonato{
        background: rgba(150, 150, 150, 0.3);
    }
    #modal-odds{
        background: rgba(207, 177, 254, 0.0);
    }
</style>
@endsection

@section('javascript')
    @include('js.minhasFuncoes')
@endsection