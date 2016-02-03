@extends('masterbox.layouts.master')

@section('gotham')
  {!! Html::gotham([
    'controller' => 'masterbox.customer.profile.index',
    'form-errors' => $errors->edit_email->has() . $errors->edit_password->has() . $errors->edit_billing->has() . $errors->edit_destination->has() . $errors->edit_spot->has()
  ]) !!}
@stop

@section('content')
  
  <div class="container">
    <div class="row">
      <div class="grid-2">
        @include('masterbox.partials.sidebar_profile')
      </div>
      <div class="grid-9">
        <div class="profile">
          <div class="profile__wrapper">
            
            {{-- Contact --}}
            {{ Form::open(['action' => 'MasterBox\Customer\ProfileController@postEditEmail', 'id' => 'form-edit-email']) }}

              {{ Form::hidden('old_password', null, ['id' => 'old-password']) }}

              <div id="email-block" class="profile__section">
                <h3 class="profile__title">Contact</h3>
                <p>Nous utilisons cette adresse email pour toutes informations relatives à ton compte.</p>
                {!! Form::label("email", "Email", ['class' => 'form__label']) !!}

                <div class="row">
                  <div class="grid-9">
                    {!! Form::email("email", ($customer->email) ? $customer->email : Request::old("email"), ['class' => 'form__input']) !!}
                  </div>
                  <div class="grid-3">
                    <button type="submit" class="button button__submit --profile">Mettre à jour</button>
                  </div>
                </div>
                {!! Html::checkError('email', $errors, 'edit_email') !!}
                {!! Html::checkError('old_password', $errors, 'edit_email') !!}
              </div>
            {{ Form::close() }}

            {{-- Security --}}
            @if (!$customer->hasProvider())
              {{ Form::open(['action' => 'MasterBox\Customer\ProfileController@postEditPassword', 'id' => 'form-edit-password']) }}

                {{ Form::hidden('old_password', null, ['id' => 'old-password']) }}

                <div id="password-block" class="profile__section">
                  <h3 class="profile__title">Sécurité</h3>
                  <p>Le mot de passe permet de protéger ton compte.</p>
                  {!! Form::label("password", "Nouveau mot de passe", ['class' => 'form__label']) !!}

                  <div class="row">
                    <div class="grid-9">
                      {!! Form::password("password", ['class' => 'form__input']) !!}
                    </div>
                    <div class="grid-3">
                      <button type="submit" class="button button__submit --profile">Mettre à jour</button>
                    </div>
                  </div>
                  {!! Html::checkError('password', $errors, 'edit_password') !!}
                  {!! Html::checkError('old_password', $errors, 'edit_password') !!}
                </div>
              {{ Form::close() }}
            @endif
            
            {{-- Billing --}}
            {{ Form::open(['action' => 'MasterBox\Customer\ProfileController@postEditBilling', 'id' => 'form-edit-billing']) }}
              
              {{ Form::hidden('old_password', null, ['id' => 'old-password']) }}

              <div id="billing-block" class="profile__section">
                <h3 class="profile__title">Facturation</h3>
                <p>Ces informations sont utilisées pour générer les factures de tes abonnements.</p>
                
                <div class="row">
                  <div class="grid-6">
                    {!! Form::label("first_name", "Prénom", ['class' => 'form__label']) !!}
                    {!! Form::text("first_name", ($customer->first_name) ? $customer->first_name : Request::old("first_name"), ['class' => 'form__input']) !!}
                    {!! Html::checkError('first_name', $errors, 'edit_billing') !!}
                  </div>

                  <div class="grid-6">
                    {!! Form::label("last_name", "Nom", ['class' => 'form__label']) !!}
                    {!! Form::text("last_name", ($customer->last_name) ? $customer->last_name : Request::old("last_name"), ['class' => 'form__input']) !!}
                    {!! Html::checkError('last_name', $errors, 'edit_billing') !!}
                  </div>
                </div>

                <div class="+spacer-extra-small"></div>
                
                {!! Form::label("phone", "Téléphone", ['class' => 'form__label']) !!}
                {!! Form::text("phone", ($customer->phone) ? $customer->phone : Request::old("phone"), ['class' => 'form__input']) !!}
                {!! Html::checkError('phone', $errors, 'edit_billing') !!}

                <div class="+spacer-extra-small"></div>

                <div class="row">
                  <div class="grid-6">
                    {!! Form::label("city", "Ville", ['class' => 'form__label']) !!}
                    {!! Form::text("city", ($customer->city) ? $customer->city : Request::old("city"), ['class' => 'form__input']) !!}
                    {!! Html::checkError('city', $errors, 'edit_billing') !!}
                  </div>

                  <div class="grid-6">
                    {!! Form::label("zip", "Code postal", ['class' => 'form__label']) !!}
                    {!! Form::text("zip", ($customer->zip) ? $customer->zip : Request::old("zip"), ['class' => 'form__input']) !!}
                    {!! Html::checkError('zip', $errors, 'edit_billing') !!}
                  </div>
                </div>

                <div class="+spacer-extra-small"></div>
              
                {!! Form::label("address", "Adresse", ['class' => 'form__label']) !!}
                {!! Form::textarea("address", ($customer->address) ? $customer->address : Request::old("address"), ['class' => 'form__input --small-textarea']) !!}
                {!! Html::checkError('address', $errors, 'edit_billing') !!}

                {!! Html::checkError('old_password', $errors, 'edit_billing') !!}

                <button class="button button__submit">Mettre à jour</button>

              </div>

            {{ Form::close() }}
            
            @if ($destination !== NULL)
              {{ Form::open(['action' => 'MasterBox\Customer\ProfileController@postEditDestination', 'id' => 'form-edit-destination']) }}
                <div id="destination-block" class="profile__section">
                  
                  {{ Form::hidden('old_password', null, ['id' => 'old-password']) }}

                  <h3 class="profile__title">Livraison</h3>
                  <p class="typography">Met à jour globalement les informations de livraison pour tes abonnements qui ne sont pas en points relais ou en cadeau. Les changement effectués sur cette partie seront effectifs <strong>{{ strtolower(Html::diffHumans(App\Models\DeliverySerie::nextOpenSeries()->first()->delivery)) }} (prochaine série)</strong>.</p>

                  <div class="row">
                    <div class="grid-6">
                      {!! Form::label("destination_first_name", "Prénom", ['class' => 'form__label']) !!}
                      {!! Form::text("destination_first_name", ($destination->first_name) ? $destination->first_name : Request::old("destination_first_name"), ['class' => 'form__input']) !!}
                      {!! Html::checkError('destination_first_name', $errors, 'edit_destination') !!}

                    </div>

                    <div class="grid-6">
                      {!! Form::label("destination_last_name", "Nom", ['class' => 'form__label']) !!}
                      {!! Form::text("destination_last_name", ($destination->last_name) ? $destination->last_name : Request::old("destination_last_name"), ['class' => 'form__input']) !!}
                      {!! Html::checkError('destination_last_name', $errors, 'edit_destination') !!}
                    </div>
                  </div>
                  
                  <div class="+spacer-extra-small"></div>

                  <div class="row">
                    <div class="grid-6">
                      {!! Form::label("destination_city", "Ville", ['class' => 'form__label']) !!}
                      {!! Form::text("destination_city", ($destination->city) ? $destination->city : Request::old("destination_city"), ['class' => 'form__input']) !!}
                      {!! Html::checkError('destination_city', $errors, 'edit_destination') !!}

                    </div>

                    <div class="grid-6">
                      {!! Form::label("destination_zip", "Code postal", ['class' => 'form__label']) !!}
                      {!! Form::text("destination_zip", ($destination->zip) ? $destination->zip : Request::old("destination_zip"), ['class' => 'form__input']) !!}
                      {!! Html::checkError('destination_zip', $errors, 'edit_destination') !!}
                    </div>
                  </div>

                  <div class="+spacer-extra-small"></div>
            
                  {!! Form::label("destination_address", "Adresse", ['class' => 'form__label']) !!}
                  {!! Form::textarea("destination_address", ($destination->address) ? $destination->address : Request::old("destination_address"), ['class' => 'form__input --small-textarea']) !!}
                  {!! Html::checkError('destination_address', $errors, 'edit_destination') !!}

                  {!! Html::checkError('old_password', $errors, 'edit_destination') !!}

                  <button class="button button__submit">Mettre à jour</button>

                </div>
              {{ Form::close() }}
            @endif

            @if ($spot !== NULL)
              {{ Form::open(['action' => 'MasterBox\Customer\ProfileController@postEditSpot', 'id' => 'form-edit-spot']) }}
                {{ Form::hidden('old_password', null, ['id' => 'old-password']) }}

                <div id="spot-block" class="profile__section">
                  <h3 class="profile__title">Point relais</h3>
                  <p class="typography">Met à jour globalement le point relais pour tes abonnements qui ne sont pas en livraison. Les différents changement effectués sur cette partie seront effectifs <strong>{{ strtolower(Html::diffHumans(App\Models\DeliverySerie::nextOpenSeries()->first()->delivery)) }} (prochaine série).</p>
                  <br/>
                  <div class="labelauty-choose-frequency">
                    @foreach ($delivery_spots as $delivery_spot)

                      {!! Form::radio('chosen_spot', $delivery_spot->id, ($spot->id == $delivery_spot->id) ? true : Request::old($delivery_spot->id), ['id' => $delivery_spot->id, 'data-labelauty' => Html::getTextCheckboxSpot($delivery_spot)]) !!}
                      
                      <div class="+spacer-extra-small"></div>

                        <a id="gmap-{{ $delivery_spot->id }}" href="{{ gmap_link_simple($delivery_spot->getFullAddress()) }}" target="_blank" class="button button__google-map +hidden">Voir sur Google map</a>

                    @endforeach
                  </div>
                  
                  {!! Html::checkError('chosen_spot', $errors, 'edit_spot') !!}
                  {!! Html::checkError('old_password', $errors, 'edit_spot') !!}

                  <button class="button button__submit">Mettre à jour</button>

                </div>
              {{ Form::close() }}
            @endif

        </div>
      </div>
    </div>
  </div>
</div>

@stop
