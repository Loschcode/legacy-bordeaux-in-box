@section('content')

  <div class="spacer20"></div>
  <div class="container profile-section">

    @if (Session::has('message'))
      <div class="js-alert-remove spyro-alert spyro-alert-success">{{ Session::get('message') }}</div>
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
        <a href="{{ url('user/logout') }}"><i class="fa fa-unlock"></i> Déconnexion</a>
      </li>
    </ul>

    <div class="tab-content col-md-9">

      <div class="tab-pane" id="contracts">
        <table class="table">

          <thead>

            <tr>
              <th>Abonnement</th>
              <th>Box</th>
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

                  @if ($profile->box()->first() != NULL)

                    {{$profile->box()->first()->title}}

                  @else

                    Aucune box

                  @endif

                </th>
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
                  {!! HTML::getReadableProfileStatus($profile->status) !!}
                </th>
                <th>
                  <a class="spyro spyro-btn spyro-btn-primary spyro-btn-sm upper" href="{{url('/profile/orders/'.$profile->id)}}"><i class="fa fa-list-alt"></i> Détails</a>
                </th>
              </tr>

              @endif

            @endforeach

          </tbody>

        </table>
      </div>

      <div class="tab-pane active" id="account">
        
        {!! Form::open(array('action' => 'ProfileController@postEdit', 'class' => 'form-component')) !!}


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
            {!! Form::email("email", ($user->email) ? $user->email : Input::old("email")) !!}


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
            {!! Form::text("first_name", ($user->first_name) ? $user->first_name : Input::old("first_name")) !!}


            @if ($errors->first('first_name'))
              <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('first_name') }}}</span>
            @endif
          </div>

          <div class="col-md-6">
            {!! Form::label("last_name", "Nom") !!}
            {!! Form::text("last_name", ($user->last_name) ? $user->last_name : Input::old("last_name")) !!}


            @if ($errors->first('last_name'))
              <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('last_name') }}}</span>
            @endif
          </div>
        </div>


        {!! Form::label("phone", "Téléphone") !!}
        {!! Form::text("phone", ($user->phone) ? $user->phone : Input::old("phone")) !!}


        @if ($errors->first('phone'))
          <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('phone') }}}</span>
        @endif


        <div class="row">

          <div class="col-md-6">
            {!! Form::label("city", "Ville") !!}
            {!! Form::text("city", ($user->city) ? $user->city : Input::old("city")) !!}


            @if ($errors->first('city'))
              <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('city') }}}</span>
            @endif
          </div>

          <div class="col-md-6">
            {!! Form::label("zip", "Code postal") !!}
            {!! Form::text("zip", ($user->zip) ? $user->zip : Input::old("zip")) !!}

            @if ($errors->first('zip'))
              <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('zip') }}}</span>
            @endif
          </div>
        </div>



        {!! Form::label("address", "Adresse") !!}<br />
        {!! Form::textarea("address", ($user->address) ? $user->address : Input::old("address")) !!}

        @if ($errors->first('address'))
          <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('address') }}}</span>
        @endif

        {!! Form::submit("Mettre à jour") !!}


        @if ($spot != NULL)

          <h3>Point relais</h3>

          @foreach ($delivery_spots as $delivery_spot)

            {!! Form::label($delivery_spot->id, $delivery_spot->readableSpot(), ['class' => 'hidden']) !!}
            {!! Form::radio('chosen_spot', $delivery_spot->id, ($spot->id == $delivery_spot->id) ? true : Input::old($delivery_spot->id), array('id' => $delivery_spot->id, 'class' => 'big')) !!}<br />

          @endforeach


        {!! Form::submit("Mettre à jour") !!}

        @endif

        @if ($destination != NULL)

          <h3>Livraison</h3>
          <div class="spyro-well spyro-well-sm">Les différents changement effectués sur cette partie seront effectifs  {{ strtolower(HTML::diffHumans(DeliverySerie::nextOpenSeries()->first()->delivery)) }}</div>

          <div class="spacer20"></div>
          <div class="row">
            <div class="col-md-6">
              {!! Form::label("destination_first_name", "Prénom") !!}
              {!! Form::text("destination_first_name", ($destination->first_name) ? $destination->first_name : Input::old("destination_first_name")) !!}

              @if ($errors->first('destination_first_name'))
                <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('destination_first_name') }}}</span>
              @endif
            </div>

            <div class="col-md-6">
              {!! Form::label("destination_last_name", "Nom") !!}
              {!! Form::text("destination_last_name", ($destination->last_name) ? $destination->last_name : Input::old("destination_last_name")) !!}

              @if ($errors->first('destination_last_name'))
                <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('destination_last_name') }}}</span>
              @endif
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              {!! Form::label("destination_city", "Ville") !!}
              {!! Form::text("destination_city", ($destination->city) ? $destination->city : Input::old("destination_city")) !!}

              @if ($errors->first('destination_city'))
                <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('destination_city') }}}</span>
              @endif
            </div>

            <div class="col-md-6">
              {!! Form::label("destination_zip", "Code postal") !!}
              {!! Form::text("destination_zip", ($destination->zip) ? $destination->zip : Input::old("destination_zip")) !!}

              @if ($errors->first('destination_zip'))
                <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('destination_zip') }}}</span>
              @endif
            </div>
          </div>


          {!! Form::label("destination_address", "Adresse") !!}<br />
          {!! Form::textarea("destination_address", ($destination->address) ? $destination->address : Input::old("destination_address")) !!}
          @if ($errors->first('destination_address'))
            <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('destination_address') }}}</span>
          @endif

        {!! Form::submit("Mettre à jour") !!}

        @endif

        {!! Form::close() !!}

      </div>

  </div>
@stop