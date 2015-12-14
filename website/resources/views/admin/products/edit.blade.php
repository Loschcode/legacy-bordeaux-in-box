@extends('layouts.admin')

@section('page')
  <i class="fa fa-bullhorn"></i> Edition produit {{$product->name}} (#{{$product->id}})
@stop

@section('buttons')

@if (URL::previous() != Request::root())
  <a href="{{URL::previous()}}#products" class="spyro-btn spyro-btn-success">Retour</a>
@endif

@stop

@section('content')

  @if (session()->has('message'))
    <div class="js-alert-remove spyro-alert spyro-alert-success">{{ session()->get('message') }}</div>
  @endif

  @include('_includes.errors', ['errors' => $errors])

  {!! Form::open(['action' => 'AdminProductsController@postEditProduct', 'files' => true]) !!}

  {!! Form::hidden("product_id", $product->id) !!}


  {!! Html::info("Attention : si vous changez le produit similaire rattaché, les filtres avancés reliés resterons INCHANGÉS, veuillez à les vérifier par la suite.") !!}

  <h4>Clonage</h4>

  <div class="form-group @if ($errors->first('master_partner_product_id')) has-error has-feedback @endif">
    {!! Form::label("master_partner_product_id", "Produit similaire") !!} 
    {!! Form::select('master_partner_product_id', $products_list, $product->master_partner_product_id, ['data-toggle' => 'chosen']) !!} <em>(Optionnel)</em>
  </div>

  <div id="checkbox-similar" class="form-group">
    <label for="past_advanced_filters">Copier les filtres avancés de l'article similaire</label>
    {!! Form::checkbox('past_advanced_filters', null, Request::old('past_advanced_filters')) !!}
  </div>


  <h4>Description &amp; Images</h4>
        
  <div class="form-group @if ($errors->first('partner_id')) has-error has-feedback @endif">
  {!! Form::label("partner_id", "Partenaire", ['class' => 'sr-only']) !!}
  {!! Form::select('partner_id', $partners_list, $product->partner_id) !!}

  <div class="form-group @if ($errors->first('category')) has-error has-feedback @endif">
  {!! Form::label("category", "Catégorie", ['class' => 'sr-only']) !!}
  {!! Form::select('category', $categories_list, $product->category) !!}


  <div class="form-group @if ($errors->first('name')) has-error has-feedback @endif">
    {!! Form::label("name", "Nom", ['class' => 'sr-only']) !!}
    {!! Form::text("name", (Request::old("name")) ? Request::old("name") : $product->name, ['class' => 'form-control', 'placeholder' => 'Nom']) !!}
  </div>

  <div class="form-group @if ($errors->first('description')) has-error has-feedback @endif">
    {!! Form::label("description", "Description", ['class' => 'sr-only']) !!}
    {!! Form::textarea("description", (Request::old("description")) ? Request::old("description") : $product->description, ['class' => 'form-control', 'placeholder' => 'Description']) !!}
  </div>

  <!-- Image -->
  <div class="form-group @if ($errors->first('image')) has-error has-feedback @endif">

    @foreach ($product->images()->get() as $image)

      <img width="100" src="{{ $image->getImageUrl() }}">
      {!! Form::checkbox('edit_images['.$image->id.']', $image->id, "checked=checked") !!}

    @endforeach

    {!! Form::file('images[0]') !!}
    {!! Form::file('images[1]') !!}
    {!! Form::file('images[2]') !!}

  </div>

  <h4>Détails technique</h4>

  <div class="form-group @if ($errors->first('size')) has-error has-feedback @endif">
  {!! Form::label("size", "Taille", ['class' => 'sr-only']) !!}
  {!! Form::select('size', $product_sizes_list, $product->size) !!}

  <div class="form-group @if ($errors->first('weight')) has-error has-feedback @endif">
    {!! Form::label("weight", "Site web", ['class' => 'sr-only']) !!}
    {!! Form::text("weight", (Request::old("weight")) ? Request::old("weight") : $product->weight, ['class' => 'form-control', 'placeholder' => 'Site web (http://www.monsite.fr)']) !!}
  </div>


  <div class="form-group @if ($errors->first('birthday_ready')) has-error has-feedback @endif">

    <h4>Filtre : boxes</h4>

    {!! Html::info("La suppression d'une box entraîne automatiquement la suppression de son filtre avancé attaché") !!}

    @foreach (Box::get() as $box)

    {!! Form::label("boxes[".$box->id."]", $box->title) !!}

      @if ($product->boxes()->where('boxes.id', '=', $box->id)->first() !== NULL)
        {!! Form::checkbox("boxes[".$box->id."]", $box->id, 'checked=checked') !!}
      @else
        {!! Form::checkbox("boxes[".$box->id."]", $box->id) !!}
      @endif

    @endforeach

    <br />

    <h4>Filtre : options</h4>

    + {!! Form::label("birthday_ready", "Anniversaire") !!}
    {!! Form::checkbox("birthday_ready", true, (Request::old("birthday_ready")) ? Request::old("birthday_ready") : $product->birthday_ready) !!}

    <br />

    + {!! Form::label("sponsor_ready", "Marraine") !!}
    {!! Form::checkbox("sponsor_ready", true, (Request::old("sponsor_ready")) ? Request::old("sponsor_ready") : $product->sponsor_ready) !!}

    <br />

    <h4>Filtre : restrictions</h4>

    - {!! Form::label("regional_only", "Régional (uniquement)") !!}
    {!! Form::checkbox("regional_only", true, (Request::old("regional_only")) ? Request::old("regional_only") : $product->regional_only) !!}

    <br />

  </div>

  {!! Form::submit("Mettre à jour ce produit", ['class' => 'spyro-btn spyro-btn-success']) !!}

  {!! Form::close() !!}


@stop