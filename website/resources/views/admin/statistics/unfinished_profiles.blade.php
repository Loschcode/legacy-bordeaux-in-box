@extends('layouts.admin')

@section('page')
	<i class="fa fa-list-alt"></i> Abandons série {{$series->delivery}} (#{{$series->id}})
@stop

@section('content')

	  @if (Session::has('message'))
	    <div class="js-alert-remove spyro-alert spyro-alert-success">{{ Session::get('message') }}</div>
	  @endif

	  @if ($errors->has())
	    <div class="js-alert-remove spyro-alert spyro-alert-danger">Erreurs dans le formulaire</div>
	  @endif

	  {!! HTML::info('Détails des créations et tentatives d\'abonnements pour la série '.$series->delivery) !!}


	  <ul class="nav nav-tabs" role="tablist">

	    <li class="active"><a href="#unfinished-table" role="tab" data-toggle="tab"><i class="fa fa-cube"></i> Abandons ({{$user_order_buildings->count()}})</a></li>

	    <li><a href="#unfinished-emails" role="tab" data-toggle="tab"><i class="fa fa-envelope-o"></i> Listing des emails</a></li>

	  </ul>


	<div class="tab-content">

	    <div class="panel panel-default">
	      <div class="panel-heading"><i class="fa fa-area-chart"></i> Progression des abandons journalières de la série {{$series->delivery}}</div>
	      <div class="panel-body">

	      <!-- Single line -->
	      @include('admin.partials.graphs.line_chart', ['config' => $config_graph_unfinished_profiles_focus])

	      </div>
	    </div>

		<!-- Tab List -->
		<div class="tab-pane active" id="unfinished-table">

      	@include('admin.partials.unfinished_profiles_table', array('user_order_buildings' => $user_order_buildings))

		</div>

		<!-- Tab List -->
		<div class="tab-pane" id="unfinished-emails">

		<div class="panel panel-default">
			<div class="panel-heading"><i class="fa fa-envelope"></i> Listing des emails des abandons de la série {{$series->delivery}}</div>
			<div class="panel-body">

				@foreach ($user_order_buildings as $order_building)

					<? $profile = $order_building->profile()->first(); ?>

					@if ($profile != NULL)

					<?= $profile->user()->first()->email ?>, 

					@endif

				@endforeach

			</div>
		</div>

		</div>

	</div>

@stop