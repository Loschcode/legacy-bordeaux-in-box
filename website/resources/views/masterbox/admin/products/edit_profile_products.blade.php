@extends('masterbox.layouts.admin')

@section('page')
  <i class="fa fa-folder-open"></i> Produits {{$order->customer_profile()->first()->customer()->first()->getFullName()}} du {{$order->delivery_serie()->first()->delivery}}
@stop

@section('buttons')

  @if (URL::previous() != Request::root())
    <a href="{{URL::previous()}}" class="spyro-btn spyro-btn-success">Retour</a>
  @endif

@stop

@section('content')

  @if (session()->has('message'))
    <div class="js-alert-remove spyro-alert spyro-alert-success">{{ session()->get('message') }}</div>
  @endif
  
  @if (session()->has('error'))
    <div class="js-alert-remove spyro-alert spyro-alert-danger">{{ session()->get('error') }}</div>
  @endif
        <table class="js-datas">

          <thead>

            <tr>
              <th>ID</th>
              <th>Produit</th>
              <th>Compatibilité</th>
              <th>Action</th>
            </tr>

          </thead>

          <tbody>

            @foreach ($order->customer_profile_products()->get() as $profile_product)

              <tr>

                <th>{{$profile_product->id}}</th>
                <th>{{$profile_product->partner_product()->first()->name}}</th>
                <th>{{$profile_product->accuracy}}%</th>

                <th>

                <a data-toggle="tooltip" title="Supprimer" class="spyro-btn spyro-btn-danger spyro-btn-sm" href="{{url('/admin/products/delete-profile-product/'.$profile_product->id)}}"><i class="fa fa-trash"></i></a>

                </th>

              </tr>

            @endforeach

            </tbody>

          </table>
       
      <div class="panel panel-default">

        <div class="panel-heading">Assigner un nouveau produit</div>
        <div class="panel-body">

          {!! Form::open(['action' => 'MasterBox\Admin\ProductsController@postAddProductToUserProfile']) !!}

          {!! Form::hidden('order_id', $order->id) !!}
          {!! Form::select("product_id", $possible_serie_products) !!}

          {!! Form::submit("Assigner ce produit") !!}

          {!! Form::close() !!}

        </div>
      </div>

@stop