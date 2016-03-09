@extends('masterbox.layouts.admin')

@section('navbar')
	@include('masterbox.admin.partials.navbar_deliveries_focus')
@stop

@section('content')
	<div class="row">
	  <div class="grid-12">
	    <h1 class="title title__section">Série {{ Html::dateFrench($series->delivery, true) }} (#{{$series->id}})</h1>
	    <h3 class="title title__subsection">Commandes</h3>
	  </div>
	</div>

	<div class="divider divider__section"></div>
	
  {!! Html::info('Voici le détail des commandes pour la série du '. Html::dateFrench($series->delivery, true)) !!}
	
	<a class="button button__default" href="{{url('/admin/deliveries/download-csv-orders-from-series/'.$series->id)}}">CSV des commandes de la série</a>

	<a class="button button__default" href="{{url('/admin/deliveries/download-csv-spots-orders-from-series/'.$series->id)}}">CSV des commandes à points relais de la série</a>

  @if ($not_paid_orders_num > 0)
  <a class="button button__default --red" href="{{url('/admin/deliveries/not-paid-orders/'.$series->id)}}">Commandes non payées ({{$not_paid_orders_num}})</a>
  @endif


  <table class="js-datatable-simple">

  	<thead>

  		<tr>
  			<th>ID</th>
  			<th>Série</th>
  			<th>Client</th>
  			<th>Adresse utilisateur</th>
  			<th>Téléphone utilisateur</th>
  			<th>Email utilisateur</th>
  			<th>Questions</th>
  			<th>Réponses</th>
  			<th>Paiement</th>
  			<th>Status</th>
  			<th>A offrir</th>
  			<th>Etat de la commande</th>
  			<th>Mode</th>
  			<th>Destination / Spot</th>
  			<th>Zone</th>
  			<th>Création</th>
  			<th>Fin préparation</th>
  			<th>Statut de la commande</th>
  			<th>Action</th>
  		</tr>

  	</thead>

  	<tbody>

  		@foreach ($orders as $order)

  			<?php $profile = $order->customer_profile()->first(); ?>

  			<tr>

  				<th>{{$order->id}}</th>
  				<th>{{$order->delivery_serie()->first()->delivery}}</th>
  				<th><a class="button button__default --green --table" href="{{ action('MasterBox\Admin\CustomersController@getFocus', ['id' => $order->customer_profile()->first()->customer()->first()->id]) }}">{{$order->customer_profile()->first()->customer()->first()->getFullName()}}</a></th>

  				<th>{{ $order->customer_profile()->first()->customer()->first()->getFullAddress()}} </th>
  				<th>{{ $order->customer_profile()->first()->customer()->first()->phone}} </th>

  				<th>{{ $order->customer_profile()->first()->customer()->first()->email}} </th>

  				<th>
  				<!-- Questions -->

  					{!! order_questions($profile, " / ") !!}


  				</th>
  				<th>

  					{!! order_answers($profile, " / ") !!}


  				</th>
  				<th>
  					{{ Html::euros($order->already_paid) }} / {{ Html::euros($order->unity_and_fees_price) }} <br/>

  					@foreach ($order->payments()->get() as $payment)

  						<a data-modal class="button button__default --green --table" href="{{ action('MasterBox\Admin\PaymentsController@getFocus', ['id' => $payment->id]) }}"><i class="fa fa-plus"></i></a>

  					@endforeach

  				</th>
  				<th>
  				{!! Html::getReadableOrderStatus($order->status) !!}
  				</th>
  				<th>{!! Html::boolYesOrNo($order->gift) !!}</th>
  				<th>{!! Html::getReadableOrderLocked($order->locked) !!}</th>
  				<th>{!! Html::getReadableTakeAway($order->take_away) !!}</th>
  				<th>{!! Html::getOrderSpotOrDestination($order) !!}</th>
  				<th>
  				@if ($order->isRegionalOrder())
  					Régional
  				@else
  					Non régional
  				@endif
  				</th>
  				<th>{{$order->created_at}}</th>
  				<th>{{$order->date_completed}}</th>
  				<th>{!! Html::getReadableOrderStatus($order->status) !!}</th>

  				<th>

  				@if ($order->date_completed != NULL)
  					<a class="button button__default --table js-confirm" data-confirm-text="L'envoi pour cette commande sera confirmé" href="{{ action('MasterBox\Admin\OrdersController@getConfirmSent', ['id' => $order->id]) }}">Envoi confirmé</a>

  				@else
  					<a class="button button__default --table js-confirm" data-confirm-text="La commande est prête pour envoi ?" href="{{ action('MasterBox\Admin\OrdersController@getConfirmReady', ['id' => $order->id]) }}">Prête pour envoi</a>

  				@endif
  				
  				<a class="button button__default --table --red js-confirm" data-confirm-text="La commande sera signalé comme problématique" href="{{ action('MasterBox\Admin\OrdersController@getConfirmProblem', ['id' => $order->id]) }}">Signaler problème</a>

  				<a class="button button__default --table --red js-confirm" data-confirm-text="La commande va être annulé" href="{{ action('MasterBox\Admin\OrdersController@getConfirmCancel', ['id' => $order->id]) }}">Annuler</a>

  				<a class="button button__default --table --red js-confirm-delete" href="{{ action('MasterBox\Admin\OrdersController@getDelete', ['id' => $order->id]) }}">Archiver</a>

  				</th>

  			</tr>

  		@endforeach

  	</tbody>

  </table>


@stop

<?php /*
@extends('masterbox.layouts.admin')

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

  @if (session()->has('message'))
    <div class="js-alert-remove spyro-alert spyro-alert-success">{{ session()->get('message') }}</div>
  @endif

  @if ($errors->has())
    <div class="js-alert-remove spyro-alert spyro-alert-danger">Erreurs dans le formulaire</div>
  @endif

  {!! Html::info('Voici le détail des commandes avec statistiques pour la box du '.$series->delivery) !!}


  <ul class="nav nav-tabs" role="tablist">

    <li class="active"><a href="#box-orders" role="tab" data-toggle="tab"><i class="fa fa-cube"></i>Commandes ({{$series->orders()->notCanceledOrders()->count()}})</a></li>

    <li><a href="#orders-spots" role="tab" data-toggle="tab"><i class="fa fa-map-marker"></i> Points relais ({{App\Models\Order::where('delivery_serie_id', '=', $series->id)->where('take_away', '=', true)->notCanceledOrders()->count()}})</a></li>

		<li><a href="#questions" role="tab" data-toggle="tab"><i class="fa fa-question"></i> Questionnaire</a></li>

    <li><a href="#orders-emails" role="tab" data-toggle="tab"><i class="fa fa-envelope-o"></i> Listing des emails ({{count($series_email_listing)}})</a></li>

  </ul>

	<div class="tab-content">

		<div class="panel panel-default">
			<div class="panel-heading"><i class="fa fa-area-chart"></i> Commandes journalières pour la série</div>
			<div class="panel-body">

				<!-- Single line -->
				@include('masterbox.admin.partials.graphs.area_chart', ['config' => $config_graph_series_orders])

			</div>
		</div>

		<!-- Tab List -->
		<div class="tab-pane active" id="box-orders">
		
			@include('masterbox.admin.partials.orders_table', array('orders' => $series->orders()->get()))

		</div>

		<!-- Tab List -->
		<div class="tab-pane" id="orders-spots">

  		{!! Html::info("Veuillez vérifier que les commandes actives reliées sont le même nombre que celles déjà livrées sur le point relais avant d'envoyer un email de confirmation. Le système se base sur les commandes effectivement reliées pour envoyer cette confirmation.") !!}

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

  		{!! Html::info("Statistiques détaillées des préférences pour la série via le questionnaire") !!}

	  	<table class="js-datas">
	  		<thead>
	  			<tr>
	  				<th>Question</th>

	  					<th>Box</th>

	  			</tr>
	  		</thead>
	  		<tbody>

	  			@foreach (App\Models\BoxQuestion::get() as $box_question)

	  				<tr>
	  					<th><strong>{{$box_question->short_question}}</strong></th>

		  					<th>

			  					@if (isset($form_stats[$box_question->id]))

			  						@foreach ($form_stats[$box_question->id] as $answer => $hit)
											{{$answer}} - {{$hit}}<br />
										@endforeach

			  					@else

			  						N/A

			  					@endif

		  					</th>

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
*/ ?>
