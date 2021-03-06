@extends('masterbox.layouts.admin')

@section('navbar')
	@include('masterbox.admin.partials.navbar_logs')
@stop

@section('content')
	
	<div class="row">
		<div class="grid-8">
			<h1 class="title title__section">Logs &amp; Configuration</h1>
			<h3 class="title title__subsection">Prises de contact</h3>
		</div>
	</div>

	{!! Html::info('Toutes les demandes faites par le formulaire de contact sont enregistrées ci-dessous (500 dernières)') !!}

	<table class="js-datatable-simple">

		<thead>

			<tr>
				<th>Id</th>
				<th>Service</th>
				<th>De</th>
				<th>Pour</th>
				<th>Date</th>
				<th>Action</th>
			</tr>

		</thead>

		<tbody>

			@foreach ($contacts as $contact)

				<tr>
					<td>{{ $contact->id }}</td>
					<td>{!! Html::getReadableContactService($contact->service) !!}</td>
					<td>{!! $contact->email !!}</td>
					<td>{!! $contact->recipient !!}</td>
					<td>{!! Html::diffHumans($contact->created_at) !!}</td>
					<td>			
						<a data-modal class="button button__default --green --table" href="{{ action('MasterBox\Admin\LogsController@getContact', ['contact_id' => $contact->id]) }}"><i class="fa fa-search"></i></a>
						<a class="button button__default --red --table js-confirm-delete" href="{{ action('MasterBox\Admin\LogsController@getDelete', ['id' => $contact->id]) }}"><i class="fa fa-trash"></i> </a>
					</td>
				</tr>

			@endforeach

		</tbody>

	</table>



@stop

<?php /*
@section('page')
	<i class="fa fa-gear"></i> Logs &amp; Configuration
@stop

@section('content')
	
	<!-- Flag for the javascript system -->
	<div id="js-page-contact"></div>
	
	<!-- Tabs -->
	<ul class="nav nav-tabs" role="tablist">
	  <li class="active"><a href="#contact" role="tab" data-toggle="tab"><i class="fa fa-life-buoy"></i> Prises de contact</a></li>
	  <li><a href="#orders-history" role="tab" data-toggle="tab"><i class="fa fa-truck"></i> Historique des commandes</a></li>
	  <li><a href="#emails-traces" role="tab" data-toggle="tab"><i class="fa fa-envelope-o"></i> Traces des emails</a></li>
		<li><a href="#profile-notes" role="tab" data-toggle="tab"><i class="fa fa-pencil"></i> Notes des abonnements</a></li>
	  <li><a href="#config" role="tab" data-toggle="tab"><i class="fa fa-cog"></i> Configuration</a></li>

	</ul>


	<div class="tab-content">

		@if (session()->has('message'))
			<div class="js-alert-remove spyro-alert spyro-alert-success">{{ session()->get('message') }}</div>
		@endif


  	<div class="tab-pane" id="orders-history">

			
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
          <th><a href="{{ action('MasterBox\Admin\CustomersController@getFocus', ['id' => $order->customer_profile()->first()->customer()->first()->id]) }}">{{$order->customer_profile()->first()->customer()->first()->getFullName()}}</a></th>

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
            {{$order->already_paid}}€ / {{$order->unity_and_fees_price}}€

            @foreach ($order->payments()->get() as $payment)

              (<a href="{{url('/admin/payments/focus/'.$payment->id)}}">+</a>)

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

            <a href="{{ url('/admin/orders/confirm-sent/'.$order->id) }}">Envoi confirmé</a> |

          @else

            <a href="{{ url('/admin/orders/confirm-ready/'.$order->id) }}">Prête pour envoi</a> |

          @endif

          <a href="{{ url('/admin/orders/confirm-problem/'.$order->id) }}">Signaler problème</a> |

          <a href="{{ url('/admin/orders/confirm-cancel/'.$order->id) }}">Annuler</a> |

          <a href="{{ url('/admin/orders/delete/'.$order->id) }}">Archiver</a>

          </th>

        </tr>

      @endforeach

    </tbody>

  </table>
		</div>

		<!-- Tab List -->
		<div class="tab-pane active" id="contact">

			{!! Html::info('Toutes les demandes faites par le formulaire de contact sont enregistrées ci-dessous') !!}

			<!-- Datas for modal bootstrap -->
			<div id="contacts-json" class="hidden">{{ $contacts->toJson() }}</div>

			<!-- Template modal -->
			<div class="modal fade" id="contact-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			        <h4 class="modal-title" id="myModalLabel"><span id="contact-title"></span></h4>
			      </div>
			      <div class="modal-body">

			      	De <a id="contact-from"></a> pour <a id="contact-to"></a>

			        <h3>Message</h3>
			        <p id="contact-message"></p>

			      </div>
			      <div class="modal-footer">
			        <button type="button" class="spyro-btn" data-dismiss="modal">Fermer</button>
			        <a id="contact-archive" class="spyro-btn spyro-btn-inverse"><i class="fa fa-archive"></i> Archiver</a>
			      </div>
			    </div>
			  </div>
			</div>


			<table class="js-datas">

				<thead>

					<tr>
						<th>Service</th>
						<th>De</th>
						<th>Pour</th>
						<th>Date</th>
						<th>Action</th>
					</tr>

				</thead>

				<tbody>

					@foreach ($contacts as $contact)

						<tr>
							<th>{!! Html::getReadableContactService($contact->service) !!}</th>
							<th>{!! $contact->email !!}</th>
							<th>{!! $contact->recipient !!}</th>
							<th>{!! Html::diffHumans($contact->created_at) !!}</th>
							<th>			
							<a data-contact="{{$contact->id}}" data-toggle="tooltip" title="Voir le message" class="spyro-btn spyro-btn-primary spyro-btn-sm"><i class="fa fa-search"></i></a>
								<a data-toggle="tooltip" title="Archiver" class="spyro-btn spyro-btn-inverse spyro-btn-sm" href="{{url('admin/logs/delete/'.$contact->id)}}"><i class="fa fa-archive"></i> </a>
							</th>
						</tr>

					@endforeach

				</tbody>

			</table>

		</div>

		<!-- Tab List -->
		<div class="tab-pane" id="profile-notes">

			<table class="js-datas">

				<thead>

					<tr>
						<th>ID</th>
						<th>Client</th>
						<th>Abonnement</th>
						<th>Auteur de la note</th>
						<th>Note</th>
						<th>Date</th>
					</tr>

				</thead>

				<tbody>

					@foreach ($profile_notes as $profile_note)

						<tr>
							<th>{{$profile_note->id}}</th>
							<th>

							@if ($profile_note->customer_profile()->first() === NULL)
							N/A
							@else
							<a href="{{ action('MasterBox\Admin\CustomersController@getFocus', ['id' => $profile_note->customer_profile()->first()->customer()->first()->id]) }}">{{$profile_note->customer_profile()->first()->customer()->first()->getFullName()}}</a>
							@endif

							</th>
							<th>

								@if ($profile_note->customer_profile()->first() !== NULL)

                    <a class="spyro-btn btn-blue {{HTML::getColorFromProfileStatus($profile_note->customer_profile()->first()->status)}}" href="{{action('MasterBox\Admin\ProfilesController@getFocus', ['id' => $profile_note->customer_profile()->first()->id])}}">
                    

                    {!! Html::getReadableProfileStatus($profile_note->customer_profile()->first()->status) !!}

                    </a><br/>

								@else
									N/A
								@endif

							</th>
							<th>

							@if ($profile_note->customer()->first() === NULL)
							N/A
							@else
							<a href="{{ action('MasterBox\Admin\CustomersController@getFocus', ['id' => $profile_note->customer()->first()->id]) }}">{{$profile_note->customer()->first()->getFullName()}}</a>
							@endif

							</th>

							<th>{{$profile_note->note}}</th>

							<th>{{$profile_note->created_at}}</th>

						</tr>

					@endforeach

				</tbody>

			</table>

		</div>

		<!-- Tab List -->
		<div class="tab-pane" id="emails-traces">

			<table class="js-datas">

				<thead>

					<tr>
						<th>ID</th>
						<th>Client</th>
						<th>Abonnement</th>
						<th>MailGun Message ID</th>
						<th>Email destinataire</th>
						<th>Sujet</th>
						<th>Préparé</th>
						<th>Envoyé</th>
						<th>Emails / images autorisés</th>
						<th>Première lecture</th>
						<th>Dernière lecture</th>
						<th>Action</th>
					</tr>

				</thead>

				<tbody>

					@foreach ($email_traces as $email_trace)

						<tr>
							<th>{{$email_trace->id}}</th>
							<th>

							@if ($email_trace->customer()->first() === NULL)
							N/A
							@else
							<a href="{{ action('MasterBox\Admin\CustomersController@getFocus', ['id' => $email_trace->customer()->first()->id]) }}">{{$email_trace->customer()->first()->getFullName()}}</a>
							@endif

							</th>
							<th>

								@if ($email_trace->customer_profile()->first() !== NULL)

                    <a class="spyro-btn btn-blue {{HTML::getColorFromProfileStatus($profile_note->customer_profile()->first()->status)}}" href="{{action('MasterBox\Admin\ProfilesController@getFocus', ['id' => $profile_note->customer_profile()->first()->id])}}">
                    

                    {!! Html::getReadableProfileStatus($profile_note->customer_profile()->first()->status) !!}

                    </a><br/>

								@else
									N/A
								@endif

							</th>
							<th>{{$email_trace->mailgun_message_id}}</th>
							<th>{{$email_trace->recipient}}</th>
							<th>{{$email_trace->subject}}</th>
							<th>{{$email_trace->prepared_at}}</th>
							<th>{{$email_trace->delivered_at}}</th>
							<th>
							@if ($email_trace->customer_profile()->first() !== NULL)
							{{$email_trace->customer_profile()->first()->customer()->first()->emails_fully_authorized}}
							@endif
							</th>
							<th>{{$email_trace->first_opened_at}}</th>
							<th>{{$email_trace->last_opened_at}}</th>

							<th>			
							<a data-lightbox data-lightbox-id="more-{{$email_trace->id}}" data-lightbox-url="/admin/logs/more/{{$email_trace->id}}" data-toggle="tooltip" title="Voir plus de détails" class="spyro-btn spyro-btn-primary spyro-btn-sm"><i class="fa fa-search"></i></a>
								<a data-toggle="tooltip" title="Supprimer" class="spyro-btn spyro-btn-inverse spyro-btn-sm" href="{{url('admin/logs/delete-email-trace/'.$email_trace->id)}}"><i class="fa fa-archive"></i> </a>
							</th>
						</tr>

					@endforeach

				</tbody>

			</table>

		</div>

		<!-- Tab Config -->
		<div class="tab-pane" id="config">

			{!! Html::info('Ci-dessous vous pouvez configurer les adresses emails destinataires pour les différents services') !!}
			{!! Form::open(array('action' => 'MasterBox\Admin\LogsController@postEditSettings')) !!}

				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Configuration</h3>
					</div>
					<div class="panel-body">

						<!-- Support tech -->
						<div class="form-group @if ($errors->first('tech_support')) has-error has-feedback @endif">
							{!! Form::label("tech_support", "Support technique", ['class' => 'control-label']) !!}
							{!! Form::text("tech_support", (Request::old('tech_support')) ? Request::old('tech_support') : $contact_setting->tech_support, ['class' => 'form-control']) !!}
							@if ($errors->first('tech_support'))
			  					<span class="glyphicon glyphicon-remove form-control-feedback"></span>
			  					<span class="help-block">{!! $errors->first('tech_support') !!}</span>
							@endif
					  </div>

						<!-- Support comm -->
						<div class="form-group @if ($errors->first('com_support')) has-error has-feedback @endif">
							{!! Form::label("com_support", "Support commercial", ['class' => 'control-label']) !!}
							{!! Form::text("com_support", (Request::old('com_support')) ? Request::old('com_support') : $contact_setting->com_support, ['class' => 'form-control']) !!}

							@if ($errors->first('com_support'))
			  					<span class="glyphicon glyphicon-remove form-control-feedback"></span>
			  					<span class="help-block">{{ $errors->first('com_support') }}</span>
							@endif

					  </div>
					  
					</div>
					<div class="panel-footer">

						<div class="text-right">
							<button type="submit" class="spyro-btn spyro-btn-success"><i class="fa fa-refresh"></i> Mettre à jour la configuration</button>
						</div>

					</div>
				</div>

			{!! Form::close() !!}

		</div>
	</div>
	

@stop
*/?>