@extends('componentes.pagina')

@section('content')
	<div class="container">

		@foreach( $bolaoDisponivel as $bolao )
			<div class="row justify-content-center">
				@include('componentes.cards.card_bolaodisponivel', $bolao)
			</div>
		@endforeach				
			
	</div>
	

@endsection

@section('css')
<style type="text/css">
	.card{
		margin-bottom: 10px;
	}
</style>
@endsection

@section('javascript')
<script type="text/javascript">
	function selecionarCampo(input){
		$(input).parent().parent().find('td').removeClass('bg-success');
		$(input).parent().addClass('bg-success');

	}
</script>
@endsection