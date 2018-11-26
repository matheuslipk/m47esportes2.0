<!DOCTYPE html>
<html>
<head>
	<title>M47ESPORTES</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

    @hasSection('css')
    	@yield('css')
    @endif

    @hasSection('javascript')
        @yield('javascript')
    @endif

    <script>
        $(document).ready(function (){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>

</head>
<body style='background: #555'>
    @guest()
        @guest('web-admin')
            @include('componentes.navbar.convidadonav')
        @endif                
    @endif

    @auth('web')
        @include('componentes.navbar.agentenav')
    @endif

    @auth('web-admin')
        @include('componentes.navbar.adminnav')
    @endif
    
	@hasSection('content')
		@yield('content')
	@endif
</body>
</html>