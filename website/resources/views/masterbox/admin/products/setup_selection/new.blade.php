
@extends('masterbox.layouts.admin')

@section('page')
  <i class="fa fa-gear"></i> Paramètrage produits série {{$serie->delivery}} (#{{$serie->id}})
@stop

@section('buttons')

@if (URL::previous() != Request::root())
  
  <a href="{{URL::previous()}}#filters" class="spyro-btn spyro-btn-success">Retour</a>

@endif

@stop

@section('content')

  @if (session()->has('message'))
    <div class="js-alert-remove spyro-alert spyro-alert-success">{{ session()->get('message') }}</div>
  @endif

  @if ($errors->has())
    <div class="js-alert-remove spyro-alert spyro-alert-danger">Erreurs dans le formulaire</div>
  @endif


  {!! Html::info('Les données statistiques (e.g. coût maximum, avantage haute priorité) sont des approximations, le résultat pourra être légèrement différent selon les produits sélectionnés.') !!}


  {!! Form::open(['action' => 'MasterBox\Admin\ProductsController@postSetupProductsSelection']) !!}

  {!! Form::hidden("serie_id", $serie->id) !!}

  <h4>Paramètres globaux par box</h4>
  <hr />

  <div class="form-group @if ($errors->first('large_products')) has-error has-feedback @endif">
  {!! Form::label("large_products", "Gros produits") !!}
  {!! Form::select('large_products', generate_unity_form(), (Request::old("large_products")) ? Request::old("large_products") : Config::get('bdxnbx.filters_setup.large_products')) !!}

  <br />

  <div class="form-group @if ($errors->first('medium_products')) has-error has-feedback @endif">
  {!! Form::label("medium_products", "Moyen produits") !!}
  {!! Form::select('medium_products', generate_unity_form(), (Request::old("medium_products")) ? Request::old("medium_products") : Config::get('bdxnbx.filters_setup.medium_products')) !!}

  <br />

  <div class="form-group @if ($errors->first('small_products')) has-error has-feedback @endif">
  {!! Form::label("small_products", "Petits produits") !!}
  {!! Form::select('small_products', generate_unity_form(), (Request::old("small_products")) ? Request::old("small_products") : Config::get('bdxnbx.filters_setup.small_products')) !!}

  <br /><br />

  <div class="form-group @if ($errors->first('max_desired_cost')) has-error has-feedback @endif">
    {!! Form::label("max_desired_cost", "Coût maximum désiré") !!}
    {!! Form::text("max_desired_cost", Request::old("max_desired_cost"), ['placeholder' => 'e.g. 10.45']) !!} € <em>(Optionnel)</em>
  </div>

  <br />

  <div class="form-group @if ($errors->first('max_desired_weight')) has-error has-feedback @endif">
    {!! Form::label("max_desired_weight", "Poids maximum désiré") !!}
    {!! Form::text("max_desired_weight", Request::old("max_desired_weight"), ['placeholder' => 'e.g. 850.10']) !!} grammes <em>(Optionnel)</em>
  </div>

  <br /><br />

  <div class="form-group @if ($errors->first('high_priority_difference')) has-error has-feedback @endif">
  {!! Form::label("high_priority_difference", "Avantage haute priorité") !!}
  {!! Form::select('high_priority_difference', generate_percent_form(), (Request::old("high_priority_difference")) ? Request::old("high_priority_difference") : Config::get('bdxnbx.filters_setup.high_priority_difference')) !!}

  <br />

  <div class="form-group @if ($errors->first('low_priority_difference')) has-error has-feedback @endif">
  {!! Form::label("low_priority_difference", "Désavantage basse priorité") !!}
  {!! Form::select('low_priority_difference', generate_percent_form(), (Request::old("low_priority_difference")) ? Request::old("low_priority_difference") : Config::get('bdxnbx.filters_setup.low_priority_difference')) !!}
  
  </div>
  
  <br /><br />

  <div class="form-group @if ($errors->first('products')) has-error has-feedback @endif">

  <h4>Sélection des produits</h4>
  <hr />
    
    <input type="text" id="search-filters" class="form-control" placeholder="Type to filter the products" />
    <div class="spacer20"></div>
    
    <div id="entries">
    @foreach ($products as $product)
    
    <div data-entry style="display: inline-block">
      {!! Form::label("products[".$product->id."]", $product->name) !!}
      {!! Form::checkbox("products[".$product->id."]", $product->id) !!}
    </div>

    @endforeach

  </div>


  {!! Form::submit("Finir le paramétrage", ['class' => 'spyro-btn spyro-btn-success']) !!}

  {!! Form::close() !!}


@stop