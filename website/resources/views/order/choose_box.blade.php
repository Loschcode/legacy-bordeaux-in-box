@extends('layouts.master')

@section('content')

	<div id="js-page-box"></div>

	@include('_includes.pipeline', ['step' => 1])
	  
	<div id="after-pipeline" class="block-description text-center">
		<div class="container">
			<div class="col-md-8 col-md-offset-2">
				<h1 class="title-step">Choisis ta box</h1>
				@if ($order_preference->gift == TRUE)
					<p>Choisis la box qui lui correspond le mieux</p>
				@else
					<p>
						Choisis la box qui te correspond le mieux !
					</p>
				@endif
			</div>
		</div>
	</div>


	<div class="spacer20"></div>

	<div class="container">
		<div id="boxes" class="select-boxes">
			{!! Form::open(array('id' => 'choose_box')) !!}

			{!! Form::hidden('box_choice', '0', array('id' => 'box_choice')) !!}

				<div id="boxes-json" class="hidden">{{ $boxes->toJson() }}</div>

				@foreach ($boxes as $box)

					<div class="col-md-4">
						<div class="col-md-10 col-md-offset-1">
							<a id="box-{{ $box->id }}" class="js-box-picture" href="#">
								<img title="{{$box->title}}" class="img-responsive inactive" src="{{ url($box->image->full) }}">
							</a>

							<div class="clearfix"></div>
				
						</div>
					</div>

				@endforeach

			{!! Form::close() !!}
		</div>
		
		<div class="clearfix"></div>
		<div class="spacer20"></div>

		<div id="box-details" class="text-center">
			<div class="col-md-8 col-md-offset-2">
				<div class="buttons-punchline text-center">
					<div id="box-title" class="box-title"></div>
					<div id="box-description" class="box-description"></div>
					<a id="box-buy" href="#" class="hidden">Je veux acheter la box <span id="box-name"></span></a>
				</div>
			</div>
		</div>
	</div>

	<div class="spacer200"></div>
	@include('_includes.footer')
@stop