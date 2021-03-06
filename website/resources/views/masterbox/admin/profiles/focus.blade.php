@extends('masterbox.layouts.admin')

@section('navbar')
  @include('masterbox.admin.partials.navbar_profiles')
@stop

@section('content')

<div class="row">
  <div class="grid-8">
    <h1 class="title title__section">Abonnement #{{ $profile->id }} 

    @if ($profile->status === 'expired')
    ({!! Html::getReadableProfileStatus($profile->status) !!})
    @endif
    
    </h1>
    <h2 class="title title__subsection">Résumé</h2>
  </div>
  <div class="grid-4">
    <div class="+text-right">
      <a href="{{ action('MasterBox\Admin\ProfilesController@getIndex') }}" class="button button__section"><i class="fa fa-list"></i> Voir tous les abonnements</a>
    </div>
  </div>
</div>

<div class="divider divider__section"></div>

<div class="row">
  <div class="grid-6">
    <div class="panel">
      <div class="panel__wrapper">
        <div class="panel__header">
          <h3 class="panel__title">Abonnement #{{ $profile->id }}</h3>
        </div>
        <div class="panel__content">
          
          <div class="typography">
            <strong>Client:</strong> <a href="{{ action('MasterBox\Admin\CustomersController@getFocus', ['id' => $customer->id]) }}">{{ $profile->customer()->first()->getFullName() }}</a><br />

            <strong>Naissance:</strong> 
            
            @if ($profile->getAge() == 0)

            Inconnu

            @else

            {{$profile->getAnswer('birthday')}} ({{$profile->getAge()}} ans)

            @endif

            <br />
            
            <div class="+spacer-extra-small"></div>

            <strong>Abonnement:</strong> {{$profile->contract_id}}<br />
            <strong>Statut:</strong> {!! Html::getReadableProfileStatus($profile->status) !!}<br/>

            {{ Form::open(array('action' => 'MasterBox\Admin\ProfilesController@postUpdatePriority')) }}
            {{ Form::hidden('profile_id', $profile->id)}}
            <strong>Priorité :</strong> {{ Form::select('priority', generate_priority_form(), $profile->priority, ['class' => 'js-chosen', 'data-width' => '100px']) }} {{ Form::submit('Valider', ['class' => 'button button__default']) }} <br/>
            {{ Form::close() }}

            <div class="+spacer-extra-small"></div>

            @if ($order_preference != NULL)

            <strong>A offrir:</strong> {{ ($order_preference->gift) ? 'Oui' : 'Non' }}<br />
            <strong>A emporter:</strong> {{ ($order_preference->take_away) ? 'Oui' : 'Non' }}<br />
            <strong>Zone (prochaines livraisons):</strong>

            @if ($profile->orders()->orderBy('id', 'desc')->first() !== NULL)

            {{ ($profile->orders()->orderBy('id', 'desc')->first()->isRegionalOrder()) ? 'Régional' : 'Non régional'}}

            @else

            N/A

            @endif

            <div class="+spacer-extra-small"></div>

            <strong>Durée :</strong> 

            @if ($order_preference->frequency == 0)
            Sans expiration
            @else
            {{ $order_preference->frequency }} mois
            @endif

            <br />
            <strong>Prix total :</strong> {{$order_preference->totalPricePerMonth()}} &euro; (unité : {{$order_preference->unity_price}} &euro;, livraison : {{$order_preference->delivery_fees}} &euro;)

            <br />
  

            @endif


            
            <div class="+spacer-extra-small"></div>
            <strong>Créé le:</strong> {{ Html::dateFrench($profile->created_at, true) }}<br />
            <strong>Dernière mise à jour le: </strong> {{ Html::dateFrench($profile->updated_at, true) }}<br/> 
          </div>

          @if ($profile->status == 'subscribed')

           <a class="button button__default --red js-confirm-delete" href="{{ action('MasterBox\Admin\ProfilesController@getCancelSubscription', ['id' => $profile->id]) }}"><i class="fa fa-times-circle"></i> Annuler cet abonnement</a>

          @endif

           @if (!$order_preference->isGift() && ($profile->status === 'subscribed'))
             
             <a class="button button__default --green" href="#switch-offer" data-modal><i class="fa fa-pencil"></i> Changer l'offre</a>
             <div class="+hidden">
               <div id="switch-offer" class="modal">
                 {{ Form::open(array('action' => 'MasterBox\Admin\ProfilesController@postUpdateOffer')) }}
                 {{ Form::hidden('profile_id', $profile->id)}}

                 <div class="dialog">
                   <h4 class="dialog__title">Changement d'offre</h4>
                   <div class="dialog__divider"></div>
                 </div>

                 {!! Html::info("N'oubliez pas de mettre à jour les informations cruciales à la donnée `A emporter` si jamais elle est changée. Les box dont la fréquence est 1 mois sont systématiquement prélevées dans l'immédiat.") !!}
                  
                    Offre: {{ Form::select('delivery_price_id', generate_delivery_prices(), '', ['class' => 'js-chosen', 'data-width' => '350px']) }}<br/>
                    A emporter: {{ Form::select('take_away', [0 => 'Non', 1 => 'Oui'], $order_preference->take_away, ['class' => 'js-chosen', 'data-width' => '100px']) }}<br/>

                    @if ($profile->orders()->orderBy('id', 'desc')->first() === NULL)
                    Frais de livraison: {{ Form::select('delivery_fees', generate_delivery_fees(FALSE), '', ['class' => 'js-chosen', 'data-width' => '250px']) }}
                    @else
                    Frais de livraison: {{ Form::select('delivery_fees', generate_delivery_fees($profile->orders()->orderBy('id', 'desc')->first()->isRegionalOrder()), '', ['class' => 'js-chosen', 'data-width' => '250px']) }}
                    @endif

                    <br />

                    Prochain prélèvement: {{ Form::select('next_charge', [0 => 'Immédiat', 15 => 'Dans 15 jours', 30 => 'Dans 30 jours'], '', ['class' => 'js-chosen', 'data-width' => '250px']) }}<br />
          
                  <div class="+spacer-extra-small"></div>
                  {{ Form::submit("Changer l'offre maintenant", ['class' => 'button button__default']) }} <br/>
                 {{ Form::close() }}

               </div>
             </div>
           
          @endif

        </div>
      </div>
    </div>
  </div>
  <div class="grid-6">
    <div class="panel">
      <div class="panel__wrapper">
        <div class="panel__header">
          <h3 class="panel__title">Notes</h3>
        </div>
        <div class="panel__content">

          @if ($profile->notes()->first() == NULL)
            Aucune note pour le moment
          @endif

          @foreach ($profile->notes()->get() as $note)
            
            <div class="note">
              <div class="note__wrapper">
                <h3 class="note__title">Ecrit par <strong> 

                @if ($note->administrator()->first() === NULL)
                  {{Config::get('bdxnbx.bot')}}
                @else
                  {{ $note->administrator()->first()->getFullName() }}
                @endif

                </strong> le <strong>{{$note->created_at->format('d/m/Y')}}</strong>

                <em>
                  ({{Html::getReadableNoteType($note->type)}}
                  @if ($note->delivery_serie()->first() !== NULL)
                  , Série {{$note->delivery_serie()->first()->delivery}}
                  @endif
                  )
                </em>

                </h3>

                <p class="note__content">{{ $note->note }}</p>
              </div>
            </div>

          @endforeach
          
          {!! Form::open(array('action' => 'MasterBox\Admin\ProfilesController@postAddNote', 'class' => 'form-inline')) !!}

            {!! Form::hidden('customer_profile_id', $profile->id) !!}
    
            {!! Form::textarea("note", Request::old('note'), ['class' => 'form__input --small-textarea']) !!}
            
            Série : {{ Form::select('serie', generate_available_series_form(), '', ['class' => 'js-chosen', 'data-width' => '350px']) }}<br/>
            Classification : {{ Form::select('type', generate_note_type_form(), '', ['class' => 'js-chosen']) }}<br/>

            {!! Html::checkError('note', $errors) !!}
            
            <div class="+spacer-extra-small"></div>
            
            {!! Form::submit("Ajouter cette note", ['class' => 'button button__default']) !!}

          {!! Form::close() !!}
        </div>
      </div>

    </div>
  </div>
</div>

@stop