<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="/">M47esportes</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="{{route('listaagentes')}}">Agentes<span class="sr-only">(current)</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{route('adminapostas')}}">Apostas</a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" href="{{route('admineventos')}}">Eventos</a>
      </li>

      <li class="nav-item">
        <form method="post" action="{{route('logout')}}">
          @csrf
          <button class="btn btn-info">Sair</button>
        </form>
      </li>
      
    </ul>
  </div>
</nav>