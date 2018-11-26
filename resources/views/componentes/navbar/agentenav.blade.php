<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="/">M47esportes</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">

      @guest()
      <li class="nav-item">
        <a class="nav-link" href="/login">Login</a>
      </li>
      @endif

      @auth('web')
      <li class="nav-item">
        <a class="nav-link" href="{{route('agenteapostas')}}">Suas apostas</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{route('agentevalidar')}}">Validar aposta</a>
      </li>
      
      <li class="nav-item">
        <form method="post" action="{{route('logout')}}">
          @csrf
          <button class="btn btn-warning">Sair</button>
        </form>
      </li>
      @endif
      
    </ul>
  </div>
</nav>