@extends('layouts.admin')

@section('page')
	<i class="fa fa-list-alt"></i> Série {{$series->delivery}} (#{{$series->id}})
@stop

@section('buttons')

	<a class="spyro-btn spyro-btn-success" href="{{url('/admin/deliveries/download-csv-orders-from-series/'.$series->id)}}">CSV des commandes de la série</a>

	<a class="spyro-btn spyro-btn-success" href="{{url('/admin/deliveries/download-csv-spots-orders-from-series/'.$series->id)}}">CSV des commandes à points relais de la série</a>

	@if (URL::previous() != Request::root())
	  
	  <a href="{{URL::previous()}}" class="spyro-btn spyro-btn-success">Retour</a>

	@endif

@stop

@section('content')

  @if (Session::has('message'))
    <div class="js-alert-remove spyro-alert spyro-alert-success">{{ Session::get('message') }}</div>
  @endif

  @if ($errors->has())
    <div class="js-alert-remove spyro-alert spyro-alert-danger">Erreurs dans le formulaire</div>
  @endif

  {!! Form::info('Voici le détail des commandes avec statistiques pour la box du '.$series->delivery) !!}


  <ul class="nav nav-tabs" role="tablist">

    <li class="active"><a href="#box-orders" role="tab" data-toggle="tab"><i class="fa fa-cube"></i>Commandes ({{$series->orders()->notCanceledOrders()->count()}})</a></li>

    <li><a href="#orders-spots" role="tab" data-toggle="tab"><i class="fa fa-map-marker"></i> Points relais ({{Order::where('delivery_serie_id', '=', $series->id)->where('take_away', '=', true)->notCanceledOrders()->count()}})</a></li>

		<li><a href="#questions" role="tab" data-toggle="tab"><i class="fa fa-question"></i> Questionnaire</a></li>

    <li><a href="#orders-emails" role="tab" data-toggle="tab"><i class="fa fa-envelope-o"></i> Listing des emails ({{count($series_email_listing)}})</a></li>

  </ul>

	<div class="tab-content">

		<div class="panel panel-default">
			<div class="panel-heading"><i class="fa fa-area-chart"></i> Commandes journalières pour la série</div>
			<div class="panel-body">

				<!-- Single line -->
				@include('admin.partials.graphs.area_chart', ['config' => $config_graph_series_orders])

			</div>
		</div>

		<!-- Tab List -->
		<div class="tab-pane active" id="box-orders">
		
			@include('admin.partials.orders_table', array('orders' => $series->orders()->get()))

		</div>

		<!-- Tab List -->
		<div class="tab-pane" id="orders-spots">

  		{!! Form::info("Veuillez vérifier que les commandes actives reliées sont le même nombre que celles déjà livrées sur le point relais avant d'envoyer un email de confirmation. Le système se base sur les commandes effectivement reliées pour envoyer cette confirmation.") !!}

	  	<table class="js-datas">

	  		<thead>

	  			<tr>
	  				<th>ID</th>
	  				<th>Nom</th>
	  				<th>Commandes actives reliées (pour la série)</th>
	  				<th>Commandes livrées reliées (pour la série)</th>
	  				<th>Téléchargements</th>
	  				<th>Action</th>
	  			</tr>

	  		</thead>

	  		<tbody>

	  			@foreach ($spots as $spot)

	  				<tr>
	  					<th>{{$spot->id}}</th>
	  					<th>{{$spot->name}}</th>
	  					<th>{{$spot->getSeriesOrders($series)->count()}}</th>
	  					<th>{{$spot->getDeliveredSeriesOrders($series)->count()}}</th>
	  					<th>
	  						<a href="{{ url('/admin/deliveries/download-csv-orders-from-series-and-spot/' . $series->id . '/' . $spot->id) }}">Commandes</a>
	  					</th>
	  					<th>

	  						<a data-toggle="confirmation" data-title="Envoyer les emails pour confirmer les livraisons au point relais ?" class="spyro-btn spyro-btn-success spyro-btn-sm" href="{{url('/admin/email-manager/send-email-to-series-spot-orders/' . $series->id . '/' . $spot->id)}}"><i class="fa fa-envelope"></i></a>

	  					</th>
	  				</tr>

	  			@endforeach

	  		</tbody>

	  	</table>

		</div>

		<!-- Tab List -->
		<div class="tab-pane" id="questions">

  		{!! Form::info("Statistiques détaillées des préférences pour la série via le questionnaire") !!}

	  	<table class="js-datas">
	  		<thead>
	  			<tr>
	  				<th>Question</th>

	  				@foreach ($boxes as $box)

	  					<th>{{$box->title}}</th>

	  				@endforeach
	  			</tr>
	  		</thead>
	  		<tbody>

	  			@foreach (BoxQuestion::get() as $box_question)

	  				<tr>
	  					<th><strong>{{$box_question->short_question}}</strong></th>

		  				@foreach ($boxes as $box)

		  					<th>
			  					@if (isset($form_stats[$box->id][$box_question->id]))

			  						@foreach ($form_stats[$box->id][$box_question->id] as $answer => $hit)
											{{$answer}} - {{$hit}}<br />
										@endforeach
			  					@else
			  						N/A
			  					@endif
		  					</th>

		  				@endforeach

	  				</tr>

	  			@endforeach

	  		</tbody>

	  	</table>

		</div>

		<!-- Tab List -->
		<div class="tab-pane" id="orders-emails">

		<div class="panel panel-default">
			<div class="panel-heading"><i class="fa fa-envelope"></i> Listing des emails de la série {{$series->title}}</div>
			<div class="panel-body">

				@foreach ($series_email_listing as $email)
					{{$email}}, 
				@endforeach

			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading"><i class="fa fa-envelope"></i> Listing des emails des profils non terminés de la série {{$series->title}}</div>
			<div class="panel-body">

				@foreach ($series_unfinished_email_listing as $email)
					{{$email}}, 
				@endforeach

			</div>
		</div>

		</div>

	</div>

@stop