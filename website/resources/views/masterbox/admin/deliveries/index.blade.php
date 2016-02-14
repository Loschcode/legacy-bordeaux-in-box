@extends('masterbox.layouts.admin')

@section('content')
    

  <div class="row">
    <div class="grid-8">
      <h1 class="title title__section">Séries</h1>
    </div>
  </div>

  <div class="divider divider__section"></div>
  
  <table class="js-datatable-simple">

    <thead>

      <tr>
        <th>ID</th>
        <th>Série</th>
        <th>Chiffres</th>
        <th>Commandes</th>
        <th>Compteur</th>
        <th>Achèvement</th>
        <th>Téléchargements</th>
        <th>Action</th>
      </tr>

    </thead>

    <tbody>

      @foreach ($series as $serie)

        <tr>

          <td>{{$serie->id}}</td>
          <td><a class="button button__default --table --green" href="{{ action('MasterBox\Admin\DeliveriesController@getFocus', ['id' => $serie->id]) }}">{{ Html::dateFrench($serie->delivery, true) }} ({{ $serie->delivery }})</a></td>
          <td>{{ Html::euros($serie->orders()->sum('already_paid'))}}</td>
          <td>
          {{$serie->orders()->notCanceledOrders()->count()}}
          </td>
          <td>
          @if ($serie->goal == 0)
          N/A
          @else
          {{$serie->getCounter()}}
          @endif
          </td>

          <td>
          @if ($serie->getAchievement() === FALSE)
          N/A
          @else
          {{ $serie->getAchievement() }} %
          @endif

          </td>

          <td>
          <a class="button button__default --table" href="{{ action('MasterBox\Admin\DeliveriesController@getDownloadCsvOrdersFromSeries', ['id' => $serie->id]) }}">Commandes</a>
          <a class="button button__default --table" href="{{ action('MasterBox\Admin\DeliveriesController@getDownloadCsvSpotsOrdersFromSeries', ['id' => $serie->id]) }}">Points relais</a>
          </td>
          <td>

          @if ($serie->closed == NULL)
            <a title="Bloquer la série" data-confirm-text="La série va être bloquée !" class="button button__default --table --red js-tooltip js-confirm" href="{{ action('MasterBox\Admin\DeliveriesController@getLock', ['id' => $serie->id]) }}"><i class="fa fa-lock"></i></a>
            <a data-modal title="Editer la série" class="button button__default --green --table js-tooltip" href="{{ action('MasterBox\Admin\DeliveriesController@getEdit', ['id' => $serie->id]) }}"><i class="fa fa-pencil"></i></a>
          @else
            <a title="Envoyer les emails pour confirmer les livraisons à domicile ?" data-confirm-text="Les emails vont être envoyés pour confirmer les livraisons à domicile (La Poste)" class="button button__default --red --table js-tooltip js-confirm" href="{{ action('MasterBox\Admin\EmailManagerController@getSendEmailToSeriesShippedOrders', ['id' => $serie->id]) }}"><i class="fa fa-envelope"></i></a>

            @if ($serie->isUnlockable())
              <a title="Débloquer" class="button button__default --table" href="{{ action('MasterBox\Admin\DeliveriesController@getUnlock', ['id' => $serie->id])}}"><i class="fa fa-unlock"></i></a>
            @endif

          @endif

          @if ($serie->orders()->first() == NULL)
            <a title="Supprimer" class="button button__default --table --red js-confirm-delete" href="{{ action('MasterBox\Admin\DeliveriesController@getDelete', ['id' => $serie->id]) }}"><i class="fa fa-trash-o"></i></a>
          @endif

        </tr>

      @endforeach

      </tbody>

    </table>
    
    <div class="+spacer-small"></div>

    <div class="grid-12">
      <div class="panel panel__wrapper">

        <div class="panel__header">
          <h3 class="panel__title">Ajouter une série</h3>
        </div>
        <div class="panel__content">
          
          {!! Form::open(['class' => 'form-inline']) !!}
            
            <div class="row">
              <div class="grid-6">
                {!! Form::label("delivery", "Date de livraison", ['class' => 'form__label']) !!}
                {!! Form::text("delivery", Request::old("delivery"), ['class' => 'form__input', 'placeholder' => 'Date de livraison']) !!}
                {!! Html::checkError('delivery', $errors) !!}
              </div>
              <div class="grid-6">
                {!! Form::label("goal", "Objectif", ['class' => 'form__label']) !!}
                {!! Form::text("goal", Request::old("goal"), ['class' => 'form__input', 'placeholder' => 'Objectif']) !!}
                {!! Html::checkError('goal', $errors) !!}
              </div>
            </div>
            
            <div class="+spacer-extra-small"></div>

            {!! Form::submit("Ajouter la série", ['class' => 'button button__default']) !!}
            
          {!! Form::close() !!}

        </div>
      </div>
    </div>

@stop

<?php /*
@extends('masterbox.layouts.admin')

@section('page')
  <i class="fa fa-bank"></i> Séries &amp; Finances
@stop

@section('content')
  @if (session()->has('message'))
    <div class="js-alert-remove spyro-alert spyro-alert-success">{{ session()->get('message') }}</div>
  @endif

  @if ($errors->has())
    <div class="js-alert-remove spyro-alert spyro-alert-danger">Erreurs dans le formulaire</div>
  @endif

  {!! Html::info("Ne pas toucher à ces chiffres si vous n'êtes pas pleinement apte à faire les modifications sur Stripe. Si vous supprimez une offre qu'un utilisateur utilise déjà, l'offre persistera chez l'utilisateur.") !!}

  <ul class="nav nav-tabs" role="tablist">
    <li class="active"><a href="#series" role="tab" data-toggle="tab"><i class="fa fa-cube"></i> Séries &amp; chiffres clés ({{ App\Models\DeliverySerie::getTotalPaid() }} €)</a></li>
    <li><a href="#offers" role="tab" data-toggle="tab"><i class="fa fa-star"></i> Offres &amp; frais divers ({{$prices->count()}})</a></li>
    <li><a href="#history" role="tab" data-toggle="tab"><i class="fa fa-history"></i> Historique des paiements ({{App\Models\Payment::getTotal()}} € / {{$payments->count()}})</a></li>
  </ul>

  <div class="tab-content">
    <!-- Tab List -->
    <div class="tab-pane active" id="series">
      <div class="panel panel-default">
        <div class="panel-heading"><i class="fa fa-area-chart"></i> Progression des commandes journalières (toutes séries confondues)</div>
        <div class="panel-body">

        <!-- Single line -->
        @include('masterbox.admin.partials.graphs.area_chart', ['config' => $config_graph_all_orders])

        </div>
      </div>

      <div class="panel panel-default">

        <div class="panel-heading">Ajouter une série</div>
        <div class="panel-body">
          
          {!! Form::open(['class' => 'form-inline']) !!}


          <div class="form-group @if ($errors->first('delivery')) has-error has-feedback @endif">
            {!! Form::label("delivery", "Date de livraison", ['class' => 'sr-only']) !!}
            {!! Form::text("delivery", Request::old("delivery"), ['class' => 'form-control', 'placeholder' => 'Date de livraison']) !!}
          </div>

          <!-- Counter -->
          <div class="form-group @if ($errors->first('goal')) has-error has-feedback @endif">
            {!! Form::label("goal", "Objectif", ['class' => 'sr-only']) !!}
            {!! Form::text("goal", Request::old("goal"), ['class' => 'form-control', 'placeholder' => 'Objectif']) !!}
          </div>

          {!! Form::submit("Ajouter la série", ['class' => 'spyro-btn spyro-btn-success']) !!}

          {!! Form::close() !!}

          <div class="spacer10"></div>

          @if ($errors->first('delivery'))
            <span class="error"><i class="fa fa-times"></i> {{ $errors->first('delivery') }}</span><br/>
          @endif

          @if ($errors->first('goal'))
            <span class="error"><i class="fa fa-times"></i> {{ $errors->first('goal') }}</span>
          @endif

        </div>
      </div>

      <table class="js-datas">

        <thead>

          <tr>
            <th>ID</th>
            <th>Série</th>
            <th>Chiffres</th>
            <th>Commandes</th>
            <th>Compteur</th>
            <th>Achèvement</th>
            <th>Téléchargements</th>
            <th>Action</th>
          </tr>

        </thead>

        <tbody>

          @foreach ($series as $serie)

            <tr>

              <th>{{$serie->id}}</th>
              <th>{{$serie->delivery}}</th>
              <th>{{$serie->orders()->sum('already_paid')}} €</th>
              <th>

              <a href="{{url('/admin/deliveries/focus/'.$serie->id)}}">{{$serie->orders()->notCanceledOrders()->count()}}</a>

              </th>
              <th>
              @if ($serie->goal == 0)
              N/A
              @else
              {{$serie->getCounter()}}
              @endif
              </th>

              <th>
              @if ($serie->getAchievement() === FALSE)
              N/A
              @else
              {{ $serie->getAchievement() }} %
              @endif

              </th>

              <th>
              <a href="{{url('/admin/deliveries/download-csv-orders-from-series/'.$serie->id)}}">Commandes</a> - 
              <a href="{{url('/admin/deliveries/download-csv-spots-orders-from-series/'.$serie->id)}}">Points relais</a>
              </th>
              <th>

              @if ($serie->closed == NULL)
                <a data-toggle="tooltip" title="Bloquer" class="spyro-btn spyro-btn-sm spyro-btn-inverse" href="{{url('/admin/deliveries/lock/'.$serie->id)}}"><i class="fa fa-lock"></i></a>
                <a data-toggle="tooltip" title="Editer" class="spyro-btn spyro-btn-sm spyro-btn-warning" href="{{url('/admin/deliveries/edit/'.$serie->id)}}"><i class="fa fa-pencil"></i></a>
              @else

                <a data-toggle="confirmation" data-title="Envoyer les emails pour confirmer les livraisons à domicile ?" class="spyro-btn spyro-btn-success spyro-btn-sm" href="{{url('/admin/email-manager/send-email-to-series-shipped-orders/' . $serie->id)}}"><i class="fa fa-envelope"></i></a>

                @if ($serie->isUnlockable())

                  <a data-toggle="tooltip" title="Débloquer" class="spyro-btn spyro-btn-sm spyro-btn-success" href="{{url('/admin/deliveries/unlock/'.$serie->id)}}"><i class="fa fa-unlock"></i></a>

                @endif

              @endif

              @if ($serie->orders()->first() == NULL)
              <a data-toggle="tooltip" title="Supprimer" class="spyro-btn spyro-btn-sm spyro-btn-danger" href="{{url('/admin/deliveries/delete/'.$serie->id)}}"><i class="fa fa-trash-o"></i></a>
              @endif

            </tr>

          @endforeach

          </tbody>

        </table>
    </div>

    <div class="tab-pane" id="offers">

      <div class="spacer20"></div>

      <div class="panel panel-default">
        <div class="panel-heading">Ajouter une offre</div>
        <div class="panel-body add-offer">
          {!! Form::open(['action' => 'MasterBox\Admin\DeliveriesController@postAddPrice', 'class' => 'form-inline']) !!}

          @if ($errors->first('unity_price'))
            {{{ $errors->first('unity_price') }}}<br />
          @endif

          @if ($errors->first('frequency'))
            {{{ $errors->first('frequency') }}}<br />
          @endif

          @if ($errors->first('title'))
            {{{ $errors->first('title') }}}<br />
          @endif

          {!! Form::text("title", Request::old('title'), ['class' => 'form-control', 'placeholder' => 'Titre (optionnel)']) !!} à 

          {!! Form::text("unity_price", Request::old('unity_price'), ['class' => 'form-control']) !!} <i class="fa fa-euro"></i> 

              <span class="for">pour</span> 
          {!! Form::text("frequency", Request::old('frequency'), ['class' => 'form-control']) !!} <span class="duration">mois</span> 

          {!! Form::select("gift", ['1' => 'A offrir', '0' => 'Pas à offrir'], null, ['class' => 'form-control']) !!}

          {!! Form::submit("Ajouter l'offre", ['class' => 'spyro-btn spyro-btn-success']) !!}

          {!! Form::close() !!}

        </div>
      </div>


      <div class="panel panel-default">

        <div class="panel-heading">Les offres</div>
        <div class="panel-body">
     
          <ul class="forms">
          @foreach ($prices as $price)
            <li>
            {!! Form::open(array('action' => 'MasterBox\Admin\DeliveriesController@postEditPrice', 'class' => 'form-inline')) !!}

            {!! Form::hidden('delivery_price_id', $price->id) !!}

            @if ($errors->first('unity_price'))
              {{{ $errors->first('unity_price') }}}<br />
            @endif

            @if ($errors->first('frequency'))
              {{{ $errors->first('frequency') }}}<br />
            @endif

            @if ($errors->first('title'))
              {{{ $errors->first('title') }}}<br />
            @endif

            {!! Form::text("title", $price->title, ['class' => 'form-control', 'placeholder' => 'Titre (optionnel)']) !!} à 

            {!! Form::text("unity_price", $price->unity_price, ['class' => 'form-control']) !!} <i class="fa fa-euro"></i>

                <span class="price">
                @if ($price->gift)

                au total

                @else

                par mois

                @endif

                pour 

              </span>
            {!! Form::text("frequency", $price->frequency, ['class' => 'form-control']) !!} <span class="duration">mois</span>

            {!! Form::submit("Modifier", ['class' => 'spyro-btn spyro-btn-warning spyro-btn-sm']) !!}
            <a href="{{url('/admin/deliveries/delete-price/'.$price->id)}}" class="spyro-btn spyro-btn-danger spyro-btn-sm">Supprimer</a>

            {!! Form::close() !!}
          </li>

          @endforeach
        </ul>
      </div>
    </div>

    <div class="panel panel-default">

      <div class="panel-heading">Frais de livraison</div>

      <div class="panel-body">

      {!! Form::open(array('action' => 'MasterBox\Admin\DeliveriesController@postEditSettings')) !!}

      @if ($errors->first('regional_delivery_fees'))
        {{{ $errors->first('regional_delivery_fees') }}}<br />
      @endif

      {!! Form::label("regional_delivery_fees", "Frais régionaux") !!}
      {!! Form::text("regional_delivery_fees", $settings->regional_delivery_fees) !!}€ 
      <br />

      @if ($errors->first('national_delivery_fees'))
        {{{ $errors->first('national_delivery_fees') }}}<br />
      @endif

      {!! Form::label("national_delivery_fees", "Frais nationaux") !!}
      {!! Form::text("national_delivery_fees", $settings->national_delivery_fees) !!}€ 
      <br />

      {!! Form::submit("Modifier", ['class' => 'spyro-btn spyro-btn-success spyro-btn-lg']) !!}

      {!! Form::close() !!}

      </div>

  </div>

  </div>

<br /><br />

    <!-- Tab List -->
    <div class="tab-pane" id="history">

      <div class="panel panel-default">
        <div class="panel-heading"><i class="fa fa-area-chart"></i> Progression du chiffre d'affaires journalier (toutes séries confondues)</div>
        <div class="panel-body">

        <!-- Single line -->
        @include('masterbox.admin.partials.graphs.area_chart', ['config' => $config_graph_all_payments])

        </div>
      </div>


      @include('masterbox.admin.partials.payments_table', array('payments' => $payments))

    </div>

@stop
*/ ?>