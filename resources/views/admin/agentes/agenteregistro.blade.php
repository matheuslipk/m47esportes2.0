@extends('componentes.pagina')

@section('content')
<div class="container">
	<h2>Cadastrar Agente</h2>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Cadastrar Agente</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('agenteregistro') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nickname" class="col-md-4 col-form-label text-md-right">Nickname</label>

                            <div class="col-md-6">
                                <input type="tel" id="nickname" class="form-control" name="nickname" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="telefone" class="col-md-4 col-form-label text-md-right">Telefone</label>

                            <div class="col-md-6">
                                <input type="tel" id="telefone" class="form-control" name="telefone" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!--Início status da conta-->

                        <div class="form-group row">
                            <label for="status_conta" class="col-md-4 col-form-label text-md-right">Status da conta</label>

                            <div class="col-md-6">
                                <select class="form-control" name="status_conta">
                                    <option selected value="1">Ativa</option>
                                    <option value="2">Inativa</option>
                                    <option value="3">Suspensa</option>
                                </select>
                            </div>
                        </div>

                        <!--Fim status da conta-->

                        <!--Início gerente-->

                        <div class="form-group row">
                            <label for="gerente" class="col-md-4 col-form-label text-md-right">Gerente</label>

                            <div class="col-md-6">
                                <select class="form-control" name="gerente">
                                    @foreach($gerentes as $gerente)
                                        <option value="{{$gerente->id}}">{{$gerente->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!--Fim gerente-->

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Senha') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirmar Senha') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Cadastrar
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
