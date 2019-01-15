@extends('componentes.pagina')

@section('content')
<div class="container">
	<h2>Editar liga</h2>
	<div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Formulário</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('adminliga', $liga->id) }}">
                        @csrf
                        
                        <div class="form-group row">
                            <label for="nickname" class="col-md-4 col-form-label text-md-right">Nome</label>

                            <div class="col-md-6">
                                <input type="tel" id="nome" class="form-control" name="nome" required value="{{ $liga->nome }}">
                            </div>
                        </div>

                      
                        <div class="form-group row">
                            <label for="status_conta" class="col-md-4 col-form-label text-md-right">Prioridade</label>

                            <div class="col-md-6">
                                <select class="form-control" name="prioridade">
                                    <option @if($liga->is_top_list == 0) selected @endif value="0">0</option>
                                    <option @if($liga->is_top_list == 1) selected @endif value="1">1</option>
                                    <option @if($liga->is_top_list == 2) selected @endif value="2">2</option>
                                    <option @if($liga->is_top_list == 3) selected @endif value="3">3</option>
                                </select>
                            </div>
                        </div>

                      

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Salvar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
		
</div>
@endsection