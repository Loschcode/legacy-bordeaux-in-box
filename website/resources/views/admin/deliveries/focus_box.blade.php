@extends('layouts.admin')

@section('page')
	<i class="fa fa-list-alt"></i> Box {{$box->title}} (#{{$box->id}})
@stop

@section('buttons')

	<a class="spyro-btn spyro-btn-success" href="{{url('/admin/deliveries/download-csv-orders-from-box/'.$box->id)}}">CSV des commandes de la box</a>
	
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

  {!! Form::info('Voici le détail des commandes avec statistiques pour la box '.$box->title) !!}


  <ul class="nav nav-tabs" role="tablist">

    <li class="active"><a href="#box-orders" role="tab" data-toggle="tab"><i class="fa fa-cube"></i>Commandes ({{$box->orders()->notCanceledOrders()->count()}})</a></li>

    <li><a href="#questions" role="tab" data-toggle="tab"><i class="fa fa-question"></i> Questionnaire</a></li>

    <li><a href="#orders-emails" role="tab" data-toggle="tab"><i class="fa fa-envelope-o"></i> Listing des emails ({{count($box_email_listing)}})</a></li>

  </ul>

	<div class="tab-content">

		<div class="panel panel-default">
			<div class="panel-heading"><i class="fa fa-area-chart"></i> Commandes journalières de la box {{$box->title}}</div>
			<div class="panel-body">

				<!-- Single line -->
				@include('admin.partials.graphs.area_chart', ['config' => $config_graph_box_orders])

			</div>
		</div>

		<!-- Tab List -->
		<div class="tab-pane active" id="box-orders">
		
			@include('admin.partials.orders_table', array('orders' => $box->orders()->get()))

		</div>


		<!-- Tab List -->
		<div class="tab-pane" id="questions">

			Section non commencée.

		</div>


		<!-- Tab List -->
		<div class="tab-pane" id="orders-emails">

		<div class="panel panel-default">
			<div class="panel-heading"><i class="fa fa-envelope"></i> Listing des emails de la box {{$box->title}}</div>
			<div class="panel-body">

				@foreach ($box_email_listing as $email)

					{{$email}}, 

				@endforeach

			</div>
		</div>


		</div>

	</div>

@stop