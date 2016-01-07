
      @if ($profile->orders()->first() != NULL)

        <table class="js-datas">

          <thead>

            <tr>
              <th>ID</th>
              <th>Série</th>
              <th>Mode de livraison</th>
              <th>Statut</th>
              <th>Montant déjà payé</th>
              <th>Prix total</th>
              <th>A offrir</th>
              <th>Etat de la commande</th>
              <th>Date complétée</th>
              <th>Date créée</th>
              <th>Action</th>
            </tr>

          </thead>

          <tbody>

            @foreach ($profile->orders()->get() as $order)

              <tr>

                <th>{{$order->id}}</th>
                <th>{{$order->delivery_serie()->first()->delivery}}</th>
                <th>
                  @if ($order->take_away)
                    Point relais ({{$order->delivery_spot()->first()->name}})
                  @else
                    @if ($order->destination()->first() == NULL)
                    En livraison (destination inconnue)
                    @else
                    En livraison ({{$order->destination()->first()->city}})
                    @endif
                  @endif
                </th>
                <th>
                  {!! Html::getReadableOrderStatus($order->status) !!}
                </th>
                <th>{{$order->already_paid}}€ 
                @if ($order->payment_way != NULL)
                  <?php $ways = Config::get('bdxnbx.payment_ways'); ?>
                  ({{$ways[$order->payment_way]}})
                @endif
                </th>
                <th>{{$order->unity_and_fees_price}}€</th>
                <th>
                  {!! Html::boolYesOrNo($order->gift) !!}
                </th>
                <th>
                  {!! Html::getReadableOrderLocked($order->locked) !!}
                </th>
                <th>{{$order->date_completed}}</th>
                <th>{{$order->created_at}}</th>

                <th>

                @if ($order->status != 'canceled')
                  <a data-toggle="tooltip" title="Annuler" class="spyro-btn spyro-btn-inverse spyro-btn-sm" href="{{url('/admin/orders/confirm-cancel/'.$order->id)}}"><i class="fa fa-gavel"></i></a>
                @endif

                <a data-toggle="tooltip" title="Supprimer" class="spyro-btn spyro-btn-danger spyro-btn-sm" href="{{url('/admin/orders/delete/'.$order->id)}}"><i class="fa fa-trash"></i></a>

                </th>

              </tr>

            @endforeach

            </tbody>

          </table>
          
          <div class="spacer10"></div>

          <a class="spyro-btn spyro-btn-success spyro-btn-lg" href="{{url('/admin/profiles/add-delivery/'.$profile->id)}}">Ajouter une livraison</a>

      @else
        <table class="js-datas">

          <thead>

            <tr>
              <th>ID</th>
              <th>Série</th>
              <th>Mode de livraison</th>
              <th>Statut</th>
              <th>Montant déjà payé</th>
              <th>Prix total</th>
              <th>A offrir</th>
              <th>Etat de la commande</th>
              <th>Date complétée</th>
              <th>Date créée</th>
              <th>Action</th>
            </tr>

          </thead>

          <tbody>

          </tbody>

        </table>
      @endif

      @if ($profile->status == 'subscribed')

       <a class="spyro-btn spyro-btn-danger spyro-btn-lg" href="{{url('/admin/profiles/cancel-subscription/'.$profile->id)}}">Annuler cet abonnement</a>

      @endif

      <div class="spacer40"></div>

      <h3>Adresse de facturation (actuelle)</h3>

      {!! Html::info("Note : l'utilisateur peut lui-même changer son adresse de facturation, le changement côté administrateur est prévu en cas de bug quelconque.") !!}

      <div class="spyro-well">

        <strong>{{$user->last_name}} {{$user->first_name}}</strong><br />
        @if ($user->city == NULL && $user->zip == NULL && $user->address == NULL)
          Aucun détails sur l'adresse de facturation.<br/>
        @else
          {{$user->city}}, {{$user->zip}}<br />
          {{$user->address}}<br />
        @endif
        <a class="spyro-btn spyro-btn-warning spyro-btn-sm" href="{{url('/admin/users/focus/'.$user->id)}}">Editer</a><br />

      </div>

      <div class="spacer40"></div>

      {!! Html::info("Note : un changement de région implique un changement de prix de livraison, le formulaire côté administrateur a été laissé libre (celui côté utilisateur est bloqué) ; il faudra manuellement donner ou récupérer la différence avec l'utilisateur via Stripe.") !!}

      <h3>Adresse de livraison (actuelle)</h3>
      
      <div class="spyro-well">
        @if ($order_destination != NULL)

        {!! Form::open(array('url' => '/admin/profiles/edit-delivery')) !!}
      
          {!! Form::hidden('user_profile_id', $profile->id) !!}
          
          <!-- Firstname -->
          <div class="form-group">
            {!! Form::label("destination_first_name", "Prénom") !!}
            {!! Form::text("destination_first_name", ($order_destination->first_name) ? $order_destination->first_name : Request::old("destination_first_name"), ['class' => 'form-control']) !!}<br/>
            @if ($errors->delivery->first('destination_first_name'))
              <span class="spyro-text-danger"><i class="fa fa-times"></i> {{ $errors->delivery->first('destination_first_name') }}</span>
            @endif
          </div>
          
          <!-- Lastname -->
          <div class="form-group">
            {!! Form::label("destination_last_name", "Nom") !!}
            {!! Form::text("destination_last_name", ($order_destination->last_name) ? $order_destination->last_name : Request::old("destination_last_name"), ['class' => 'form-control']) !!}<br />

            @if ($errors->delivery->first('destination_last_name'))
              <span class="spyro-text-danger"><i class="fa fa-times"></i> {{ $errors->delivery->first('destination_last_name') }}</span>
            @endif
          </div>

          
          <!-- City -->
          <div class="form-group">        
            {!! Form::label("destination_city", "Ville") !!}
            {!! Form::text("destination_city", ($order_destination->city) ? $order_destination->city : Request::old("destination_city"), ['class' => 'form-control']) !!}<br/>

            @if ($errors->delivery->first('destination_city'))
              <span class="spyro-text-danger"><i class="fa fa-times"></i> {{ $errors->delivery->first('destination_city') }}</span>
            @endif

          </div>

          <!-- Zip -->
          <div class="form-group">
            {!! Form::label("destination_zip", "Code postal") !!}
            {!! Form::text("destination_zip", ($order_destination->zip) ? $order_destination->zip : Request::old("destination_zip"), ['class' => 'form-control']) !!}<br />

            @if ($errors->delivery->first('destination_zip'))
              <span class="spyro-text-danger"><i class="fa fa-times"></i> {{ $errors->delivery->first('destination_zip') }}</span>
            @endif
          </div>
          
          <!-- Address -->
          <div class="form-group">
            {!! Form::label("destination_address", "Adresse") !!}<br />
            {!! Form::textarea("destination_address", ($order_destination->address) ? $order_destination->address : Request::old("destination_address"), ['class' => 'form-control']) !!}<br />

            @if ($errors->delivery->first('destination_address'))
              <span class="spyro-text-danger"><i class="fa fa-times"></i> {{ $errors->delivery->first('destination_address') }}</span>
            @endif
          </div>
          
          <div class="spacer20"></div>

          {!! Form::submit("Valider", ['class' => 'spyro-btn spyro-btn-success spyro-btn-lg']) !!}

        {!! Form::close() !!}


        @else
          Aucun détails
          <br /><br />
          <a class="spyro-btn spyro-btn-success spyro-btn-lg" href="{{url('/admin/profiles/generate-delivery-address/'.$profile->id)}}">Générer l'adresse de livraison depuis la facturation</a>
        @endif
      </div>

      <h3>Point relais (actuel)</h3>
      
      <div class="spyro-well">

        @if ($order_delivery_spot != NULL)

        {!! Form::open(array('url' => '/admin/profiles/edit-spot')) !!}
      
          {!! Form::hidden('user_profile_id', $profile->id) !!}

          @foreach ($delivery_spots as $delivery_spot)

            @if ($delivery_spot->id == $order_delivery_spot->id)
              {!! Form::radio('selected_spot', $delivery_spot->id, true, array('id' => $delivery_spot->id)) !!}
            @else
              {!! Form::radio('selected_spot', $delivery_spot->id, false, array('id' => $delivery_spot->id)) !!}
            @endif
            
            {!! Form::label($delivery_spot->id, $delivery_spot->name) !!}<br />

          @endforeach

          <br />

          {!! Form::submit("Valider", ['class' => 'spyro-btn spyro-btn-success spyro-btn-lg']) !!}

        {!! Form::close() !!}


        @else
          Aucun détails
        @endif
      </div>
