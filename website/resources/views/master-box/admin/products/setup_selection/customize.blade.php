@extends('layouts.admin')

@section('page')
  <i class="fa fa-gear"></i> Personnalisation produits {{$serie->delivery}} (#{{$serie->id}})
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

  {!! Html::info('Les données déjà présentes ci-dessous sont générées à travers les produits des dernières séries. Le système retournera une erreur si vous ne remplissez pas tous les champs (0 n\'est pas une valeur acceptable') !!}

  {!! Form::open(['action' => 'Admin\ProductsController@postCustomizeProductsSelection']) !!}

  <table id="table-customize-products">

    <thead>

      <tr>

        <th>Partenaire</th>
        <th>Nom du produit</th>
        <th>Coût par unité (€)</th>
        <th>Valeur par unité (€)</th>
        <th>Quantité</th>

      </tr>

    </thead>

  <tbody>

    @foreach ($serie->serie_products()->get() as $serie_product)

      <tr>

        <td>{{$serie_product->product()->first()->partner()->first()->name}}</td>
        <td>{{$serie_product->product()->first()->name}}</td>
        <td>
        {!! Form::text('products['.$serie_product->id . "][cost_per_unity]", $serie_product->getDataOrPrevious('cost_per_unity'), ['placeholder' => 'e.g. 9.50']) !!}
        </td>
        <td>
        {!! Form::text('products['.$serie_product->id . "][value_per_unity]", $serie_product->getDataOrPrevious('value_per_unity'), ['placeholder' => 'e.g. 20.10']) !!}
        </td>
        <td>
        {!! Form::text('products['.$serie_product->id . "][quantity]", $serie_product->getDataOrPrevious('quantity'), ['placeholder' => 'e.g. 15']) !!}
        </td>

      </tr>

    @endforeach

  </tbody>
  </table>

  <br />

  {!! Form::submit("Finir la personnalisation des produits", ['class' => 'spyro-btn spyro-btn-success']) !!}

  {!! Form::close() !!}


@stop