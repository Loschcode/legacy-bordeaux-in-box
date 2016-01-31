@extends('masterbox.layouts.admin')

@section('content')

<div
  id="gotham"
  data-form-errors="{{ $errors->has() }}"
  data-success-message="{{ session()->get('message') }}"
></div>

@include('masterbox.admin.partials.navbar_profiles')


<div class="row">
  <div class="grid-8">
    <h1 class="title title__section">Abonnements</h1>
    <h2 class="title title__subsection">Résumé</h2>
  </div>
  <div class="grid-4">
    <div class="+text-right">
      <a href="{{ action('MasterBox\Admin\ProfilesController@getIndex') }}" class="button button__section"><i class="fa fa-arrow-left"></i> Retour aux abonnements</a>
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
        <div class="panel__content typography">

          <strong>Client:</strong> <a href="{{ action('MasterBox\Admin\CustomersController@getFocus', ['id' => $customer->id]) }}">{{ $profile->customer()->first()->getFullName() }}</a><br />

          <strong>Naissance:</strong> {{$profile->getAnswer('birthday')}} ({{$profile->getAge()}} ans)<br />
          
          <div class="+spacer-extra-small"></div>

          <strong>Abonnement:</strong> {{$profile->contract_id}}<br />
          <strong>Statut:</strong> {!! Html::getReadableProfileStatus($profile->status) !!}<br/> 

          <div class="+spacer-extra-small"></div>

          @if ($order_preference != NULL)

          <strong>A offrir:</strong> {{ ($order_preference->gift) ? 'Oui' : 'Non' }}<br />
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
          @endif
          
          <div class="+spacer-extra-small"></div>
          <strong>Créé le:</strong> {{ Html::dateFrench($profile->created_at, true) }}<br />
          <strong>Dernière mise à jour le: </strong> {{ Html::dateFrench($profile->updated_at, true) }}<br/> 


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
                <h3 class="note__title">Ecrit par <strong>{{ $note->customer()->first()->getFullName() }}</strong> le <strong>{{$note->created_at->format('d/m/Y')}}</strong></h3>

                <p class="note__content">{{ $note->note }}</p>
              </div>
            </div>

          @endforeach
          
          {!! Form::open(array('action' => 'MasterBox\Admin\ProfilesController@postAddNote', 'class' => 'form-inline')) !!}

            {!! Form::hidden('customer_profile_id', $profile->id) !!}
    
            {!! Form::textarea("note", Request::old('note'), ['class' => 'form__input --small-textarea']) !!}

            {!! Html::checkError('note', $errors) !!}

            {!! Form::submit("Ajouter cette note", ['class' => 'button button__submit --small']) !!}

          {!! Form::close() !!}
        </div>
      </div>

    </div>
  </div>
</div>

@stop