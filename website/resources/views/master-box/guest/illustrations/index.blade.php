@section('content')
	
	<div class="container illus">

		<div class="spacer50"></div>
		<div class="col-md-8 col-md-offset-2">
			@if ($image_article === NULL)
				<div class="spyro-well text-center">Aucun potin pour le moment</div>
			@else

				<div class="text-center">
					<h1>{{$image_article->title}}</h1><br />
				</div>

				<img class="img-responsive thumbnail img-align" src="{{ url($image_article->image->full) }}">

				<div class="text-center">

					<div class="col-md-4 col-md-offset-4">
						@if ($previous_article !== NULL || $next_article !== NULL)
							<nav>
							  <ul class="pager">
							  	@if ($previous_article !== NULL)
							  		<li class="previous"><a href="{{url('illustrations/index/'.$previous_article->id)}}">&larr; Ancien</a></li>
							  	@else
							  		<li class="previous disabled"><a href="#">&larr; Ancien</a></li>
							  	@endif

							  	@if ($next_article !== NULL)
							    	<li class="next"><a href="{{url('illustrations/index/'.$next_article->id)}}">Suivant  &rarr;</a></li>
							  	@else
							  		<li class="next disabled"><a href="#">Suivant &rarr;</a></li>
							  	@endif
							  </ul>
							</nav>
						@endif
					</div>
				</div>
			@endif
		</div>
		<div class="clearfix"></div>
	</div>

	<div class="spacer100"></div>

	@if ($image_article === NULL)
		{!! View::make('master-box.partials.front.footer')->with('stick', true) !!}
	@else
		{!! View::make('master-box.partials.front.footer') !!}
	@endif
@stop