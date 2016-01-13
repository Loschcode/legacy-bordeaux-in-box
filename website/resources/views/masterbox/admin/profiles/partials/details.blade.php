
    <div class="panel panel-default">
      <div class="panel-heading"><i class="fa fa-list-alt"></i> Abonnement de {{$profile->customer()->first()->getFullName()}} (#{{$profile->customer()->first()->id}})</div>

      <div class="panel-body">

        <strong>Utilisateur :</strong> <a href="/admin/users/focus/{{$profile->customer()->first()->id}}">{{ ucfirst(mb_strtolower($profile->customer()->first()->getFullName())) }}</a><br />

        <strong>Marraine :</strong> {!! Html::generateAdminLinkFromUserEmail($profile->getAnswer('sponsor')) !!}<br />
        <strong>Naissance :</strong> {{$profile->getAnswer('birthday')}} ({{$profile->getAge()}} ans)<br />

        <br />

         <strong>Abonnement</strong> {{$profile->contract_id}}<br />
        <strong>Status :</strong> {!! Html::getReadableProfileStatus($profile->status) !!}<br/> 

        {!! Form::open(array('action' => 'MasterBox\Admin\ProfilesController@postUpdatePriority')) !!}
        {!! Form::hidden('profile_id', $profile->id) !!}
        <strong>Priorité :</strong> {!! Form::select('priority', generate_priority_form(), $profile->priority) !!} {!! Form::submit('Valider') !!} <br/>
        {!! Form::close() !!}

        <br />

        <strong>Box :</strong> {{ ($box != NULL) ? $box->title : 'Non sélectionné' }}
        <br />
        @if ($order_preference != NULL)

          <strong>A offrir :</strong> {{ ($order_preference->gift) ? 'Oui' : 'Non' }}<br />
          <strong>Zone (prochaines livraisons) :</strong>

          @if ($profile->orders()->orderBy('id', 'desc')->first() !== NULL)

           {{ ($profile->orders()->orderBy('id', 'desc')->first()->isRegionalOrder()) ? 'Régional' : 'Non régional'}}

          @else

            N/A

          @endif

           <br />
          <strong>Durée :</strong> 

          @if ($order_preference->frequency == 0)
            Sans expiration
          @else
            {{ $order_preference->frequency }} mois
          @endif

          <br />
          <strong>Prix total :</strong> {{$order_preference->totalPricePerMonth()}} &euro; (unité : {{$order_preference->unity_price}} &euro;, livraison : {{$order_preference->delivery_fees}} &euro;)
        @endif

        <br /><br />

         <strong>Créé le </strong> {{$profile->created_at->format('Y-m-d H:i:s')}}<br />
        <strong>Dernière mise à jour le </strong> {{$profile->updated_at->format('Y-m-d H:i:s')}}<br/> 


      </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading"><i class="fa fa-calendar-o"></i> Notes pour cet abonnement</div>

      <div class="panel-body">

      @if ($profile->notes()->first() == NULL)
      Aucune note pour le moment
      @endif

      @foreach ($profile->notes()->get() as $note)

      <div class="spyro-alert spyro-alert-primary">

          Ecrit par <strong>{{$note->customer()->first()->getFullName()}}</strong> le <strong>{{$note->created_at->format('d/m/Y')}}</strong>

          <div class="spacer10"></div>

          {{$note->note}}

          <div class="spacer20"></div>

      </div>

      @endforeach

      <div class="spacer10"></div>

      {!! Form::open(array('action' => 'MasterBox\Admin\ProfilesController@postAddNote', 'class' => 'form-inline')) !!}

        {!! Form::hidden('customer_profile_id', $profile->id) !!}

          @if ($errors->first('note'))
          {{{ $errors->first('note') }}}<br />
          @endif

          {!! Form::textarea("note", Request::old('note'), ['class' => 'form-control']) !!}

          <div class="spacer10"></div>

          {!! Form::submit("Ajouter cette note", ['class' => 'spyro-btn spyro-btn-success']) !!}

          {!! Form::close() !!}

      </div>