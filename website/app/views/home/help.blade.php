@section('content')
	
	<div class="page">
		<div class="container">
			<h1 class="page-title">Questions souvent posées</h1>
      <div class="col-md-10 col-md-offset-1">
				<div class="description">{{ nl2br($help->content) }}</div>
      </div>
		</div>
	</div>

  <div class="container">
    <div class="description text-center">
      Impossible de trouver une réponse à ta question ? Besoin d'une aide spécifique ? N'hésite pas à <a href="{{ url('/contact') }}">nous contacter</a> !
    </div>
  </div>

  <br/><br/>

  <div class="spacer150"></div>

  <div class="footer-container">
    @include('_includes.footer')
  </div>
@stop