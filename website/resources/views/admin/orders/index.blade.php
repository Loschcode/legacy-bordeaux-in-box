@extends('layouts.admin')

@section('page')
	<i class="fa fa-truck"></i> Suivi des commandes
@stop

@section('content')

	@if (Session::has('message'))
	  <div class="js-alert-remove spyro-alert spyro-alert-success">{{ Session::get('message') }}</div>
	@endif

	@if ($errors->has())
	  <div class="js-alert-remove spyro-alert spyro-alert-danger">Erreurs dans le formulaire</div>
	@endif

	<ul class="nav nav-tabs" role="tablist">

	  <li class="active"><a href="#to-pack" role="tab" data-toggle="tab">A préparer ({{$locked_orders->count()}})</a></li>

	  <li><a href="#to-send" role="tab" data-toggle="tab">A envoyer ({{$packed_orders->count()}})</a></li>

	  <li><a href="#problems" role="tab" data-toggle="tab">Problématiques ({{$problem_orders->count()}})</a></li>

	</ul>


	<div class="tab-content">

		<!-- Tab List -->
		<div class="tab-pane active" id="to-pack">
			
			<div class="spacer20"></div>

			<a class="spyro-btn spyro-btn-default spyro-btn-lg">Actions <i class="fa fa-angle-double-down"></i></a>
			<a class="spyro-btn spyro-btn-danger" href="{{ url('admin/orders/everything-is-ready') }}">Tout est prêt</a>

			<a class="spyro-btn spyro-btn-success" href="{{ url('admin/orders/download-csv-locked-orders') }}">Télécharger le fichier CSV des commandes à préparer</a>
			<a class="spyro-btn spyro-btn-success" href="{{ url('admin/orders/email-locked-orders') }}">Liste des Emails</a>
			<div class="spacer20"></div>

			@include('admin.partials.orders_table', array('orders' => $locked_orders))

		</div>

		<!-- Tab List -->
		<div class="tab-pane" id="to-send">
			
			<div class="spacer20"></div>

			<a class="spyro-btn spyro-btn-danger" href="{{ url('admin/orders/everything-has-been-sent') }}">Tout est envoyé</a>

			<a class="spyro-btn spyro-btn-success" href="{{ url('admin/orders/download-csv-ready-orders') }}">Télécharger le fichier CSV des commandes à envoyer</a>

			<div class="spacer20"></div>

			@include('admin.partials.orders_table', array('orders' => $packed_orders))

		</div>

	    <div class="tab-pane" id="problems">

	    	@include('admin.partials.orders_table', array('orders' => $problem_orders))

		</div>

	</div>

@stop