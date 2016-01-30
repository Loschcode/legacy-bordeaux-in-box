@extends('masterbox.layouts.master')

@section('content')
  
  <div
    id="gotham"
    data-controller="masterbox.customer.profile."
    data-form-errors="{{ $errors->edit_email->has() }} {{ $errors->edit_password->has() }} {{ $errors->edit_billing->has() }} {{ $errors->edit_destination->has() }} {{ $errors->edit_spot->has() }}"
    data-success-message="{{ session()->get('message') }}"
  ></div>

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

                      <a id="gmap-{{ $delivery_spot->id }}" href="{{ $delivery_spot->getUrlGoogleMap() }}" target="_blank" class="button button__google-map +hidden">Voir sur Google map</a>

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

<?php /*
@section('content')

  <div class="spacer20"></div>
  <div class="container profile-section">

    @if (session()->has('message'))
      <div class="js-alert-remove spyro-alert spyro-alert-success">{{ session()->get('message') }}</div>
    @endif

    <ul class="nav-tabs tabs col-md-2">
      <li class="active"><a href="#account" role="tab" data-toggle="tab"><i class="fa fa-cog"></i>Mon compte</a></li>

      <li><a href="#contracts" role="tab" data-toggle="tab">
        <i class="fa fa-shopping-cart"></i> Abonnements
      </a></li>

      <li>
        <a href="{{ url('contact') }}"><i class="fa fa-envelope-o"></i> Contact</a>
      </li>
      <li>
        <a href="{{ action('MasterBox\Connect\CustomerController@getLogout') }}"><i class="fa fa-unlock"></i> Déconnexion</a>
      </li>
    </ul>

    <div class="tab-content col-md-9">

      <div class="tab-pane" id="contracts">
        <table class="table">

          <thead>

            <tr>
              <th>Abonnement</th>
              <th>Durée</th>
              <th>Livraisons restantes</th>
              <th>Statut</th>
              <th>Action</th>

            </tr>

          </thead>

          <tbody>

            @foreach ($profiles as $profile)

              @if ($profile->orders()->first() != NULL)

              <tr>

                <th>N°{{$profile->id}}</th>
                <th>

                @if ($profile->order_preference()->first()->frequency == 0)
                  Non précisée
                @else
                  {{$profile->orders()->notCanceledOrders()->count()}} mois
                @endif
                </th>
                <th>
                @if ($profile->order_preference()->first()->frequency == 0)
                  Non indiqué
                @else
                  {{$profile->orders()->whereNull('date_sent')->count()}}
                @endif
                </th>
                <th>
                  {!! Html::getReadableProfileStatus($profile->status) !!}
                </th>
                <th>
                  <a class="spyro spyro-btn spyro-btn-primary spyro-btn-sm upper" href="{{action('MasterBox\Customer\ProfileController@getOrders', ['id' => $profile->id])}}"><i class="fa fa-list-alt"></i> Détails</a>
                </th>
              </tr>

              @endif

            @endforeach

          </tbody>

        </table>
      </div>

      <div class="tab-pane active" id="account">
        
        {!! Form::open(array('action' => 'MasterBox\Customer\ProfileController@postEdit', 'class' => 'form-component')) !!}


        {!! Form::label("old_password", "Mot de passe actuel") !!}
        {!! Form::password("old_password", '') !!}

        @if ($errors->first('old_password'))
          <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('old_password') }}}</span>
        @endif

        <div class="spyro-well spyro-well-sm">Le mot de passe actuel est requis pour tout changement d'informations</div>
        <div class="spacer50"></div>

        <div class="row">

          <div class="col-md-6">
            <h3>Contact</h3>

            {!! Form::label("email", "Email") !!}
            {!! Form::email("email", ($customer->email) ? $customer->email : Request::old("email")) !!}


            @if ($errors->first('email'))
              <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('email') }}}</span>
            @endif


          </div>

          <div class="col-md-6">
            <h3>Sécurité</h3>

            @if ($errors->first('new_password'))
              <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('new_password') }}}</span>
            @endif

            {!! Form::label("new_password", "Nouveau mot de passe") !!}
            {!! Form::password("new_password", '') !!}
          </div>

            {!! Form::submit("Mettre à jour") !!}

        </div>
        

        <h3>Facturation</h3>

        <div class="row">

          <div class="col-md-6">
            {!! Form::label("first_name", "Prénom") !!}
            {!! Form::text("first_name", ($customer->first_name) ? $customer->first_name : Request::old("first_name")) !!}


            @if ($errors->first('first_name'))
              <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('first_name') }}}</span>
            @endif
          </div>

          <div class="col-md-6">
            {!! Form::label("last_name", "Nom") !!}
            {!! Form::text("last_name", ($customer->last_name) ? $customer->last_name : Request::old("last_name")) !!}


            @if ($errors->first('last_name'))
              <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('last_name') }}}</span>
            @endif
          </div>
        </div>


        {!! Form::label("phone", "Téléphone") !!}
        {!! Form::text("phone", ($customer->phone) ? $customer->phone : Request::old("phone")) !!}


        @if ($errors->first('phone'))
          <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('phone') }}}</span>
        @endif


        <div class="row">

          <div class="col-md-6">
            {!! Form::label("city", "Ville") !!}
            {!! Form::text("city", ($customer->city) ? $customer->city : Request::old("city")) !!}


            @if ($errors->first('city'))
              <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('city') }}}</span>
            @endif
          </div>

          <div class="col-md-6">
            {!! Form::label("zip", "Code postal") !!}
            {!! Form::text("zip", ($customer->zip) ? $customer->zip : Request::old("zip")) !!}

            @if ($errors->first('zip'))
              <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('zip') }}}</span>
            @endif
          </div>
        </div>



        {!! Form::label("address", "Adresse") !!}<br />
        {!! Form::textarea("address", ($customer->address) ? $customer->address : Request::old("address")) !!}

        @if ($errors->first('address'))
          <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('address') }}}</span>
        @endif

        {!! Form::submit("Mettre à jour") !!}


        @if ($spot != NULL)

          <h3>Point relais</h3>

          @foreach ($delivery_spots as $delivery_spot)

            {!! Form::label($delivery_spot->id, $delivery_spot->readableSpot(), ['class' => 'hidden']) !!}
            {!! Form::radio('chosen_spot', $delivery_spot->id, ($spot->id == $delivery_spot->id) ? true : Request::old($delivery_spot->id), array('id' => $delivery_spot->id, 'class' => 'big')) !!}<br />

          @endforeach


        {!! Form::submit("Mettre à jour") !!}

        @endif

        @if ($destination != NULL)

          <h3>Livraison</h3>
          <div class="spyro-well spyro-well-sm">Les différents changement effectués sur cette partie seront effectifs  {{ strtolower(Html::diffHumans(App\Models\DeliverySerie::nextOpenSeries()->first()->delivery)) }}</div>

          <div class="spacer20"></div>
          <div class="row">
            <div class="col-md-6">
              {!! Form::label("destination_first_name", "Prénom") !!}
              {!! Form::text("destination_first_name", ($destination->first_name) ? $destination->first_name : Request::old("destination_first_name")) !!}

              @if ($errors->first('destination_first_name'))
                <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('destination_first_name') }}}</span>
              @endif
            </div>

            <div class="col-md-6">
              {!! Form::label("destination_last_name", "Nom") !!}
              {!! Form::text("destination_last_name", ($destination->last_name) ? $destination->last_name : Request::old("destination_last_name")) !!}

              @if ($errors->first('destination_last_name'))
                <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('destination_last_name') }}}</span>
              @endif
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              {!! Form::label("destination_city", "Ville") !!}
              {!! Form::text("destination_city", ($destination->city) ? $destination->city : Request::old("destination_city")) !!}

              @if ($errors->first('destination_city'))
                <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('destination_city') }}}</span>
              @endif
            </div>

            <div class="col-md-6">
              {!! Form::label("destination_zip", "Code postal") !!}
              {!! Form::text("destination_zip", ($destination->zip) ? $destination->zip : Request::old("destination_zip")) !!}

              @if ($errors->first('destination_zip'))
                <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('destination_zip') }}}</span>
              @endif
            </div>
          </div>


          {!! Form::label("destination_address", "Adresse") !!}<br />
          {!! Form::textarea("destination_address", ($destination->address) ? $destination->address : Request::old("destination_address")) !!}
          @if ($errors->first('destination_address'))
            <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('destination_address') }}}</span>
          @endif

        {!! Form::submit("Mettre à jour") !!}

        @endif

        {!! Form::close() !!}

      </div>

  </div>
@stop
*/ ?>