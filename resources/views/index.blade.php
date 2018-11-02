@extends('componentes.pagina')

@section('content')
    <div class="container-fluid">
    @for($i=0; $i<3; $i++)
        <div id="accordion{{$i}}">
            <div class="card">
                <div class="card-header bg-secondary" data-toggle="collapse" data-target='#collapseOne{{$i}}'>
                    <div>Brasil - Série A</div>
                </div>
                <div id="collapseOne{{$i}}" class="collapse show" data-parent="#accordion{{$i}}">
                    <div class="card-body">
                        <table border="0" class="center table table-sm">
                            <thead>
                                <tr>
                                    <th>Times</th>
                                    <th>Casa</th>
                                    <th>Empate</th>
                                    <th>Fora</th>
                                    <th>+opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($j=0; $j<3; $j++)
                                <tr>
                                    <td>Time 1 x Time 2</td>
                                    <td><button class="btn btn-sm">2.00</button></td>
                                    <td><button class="btn btn-sm">3.00</button></td>
                                    <td><button class="btn btn-sm">2.00</button></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" data-toggle='modal' data-target='#myModal'>
                                            <span class="badge badge-light">+4</span>
                                        </button>
                                    </td>
                                </tr>
                                @endfor
                                
                            </tbody>
                        </table>
                       
                    </div>
                </div>
            </div>
        </div>        
    @endfor
    </div>


        <!-- O Modal -->
        <div class="modal" id="myModal">
          <div class="modal-dialog">
            <div class="modal-content">

              <!-- Modal Header -->
              <div class="modal-header">
                <h4 class="modal-title">Cabeçalho do modal</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>

              <!-- Modal body -->
              <div class="modal-body">
                Corpo do modal
              </div>

            </div>
          </div>
        </div>

    </div>
@endsection


@section('css')
<style type="text/css">
    .conteiner{
        background: #CCC;
    }
</style>
@endsection

