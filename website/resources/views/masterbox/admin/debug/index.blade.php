@extends('masterbox.layouts.admin')

@section('page')
  <i class="fa fa-bug"></i> Debug
@stop

@section('buttons')

@stop

@section('content')

  @if (session()->has('message'))
    <div class="js-alert-remove spyro-alert spyro-alert-success">{{ session()->get('message') }}</div>
  @endif

  @if (session()->has('error'))
    <div class="js-alert-remove spyro-alert spyro-alert-error">{{ session()->get('error') }}</div>
  @endif

  {!! Html::info("Section permettant de repèrer les bugs liés au site (paiement n'étant pas relié à une commande par exemple") !!}


  @if ($errors->has())
    <div class="js-alert-remove spyro-alert spyro-alert-danger">Erreurs dans le formulaire</div>
  @endif

  <ul class="nav nav-tabs" role="tablist">

    <li class="active"><a href="#payments" role="tab" data-toggle="tab"><i class="fa fa-thumbs-up"></i> Paiements orphelins ({{$payments->count()}})</a></li>

    <li><a href="#refund" role="tab" data-toggle="tab"><i class="fa fa-thumbs-down"></i> Remboursements orphelins ({{$refunded_payments->count()}})</a></li>

    <li><a href="#series_refund" role="tab" data-toggle="tab"><i class="fa fa-thumbs-down"></i> Remboursements non orphelins ({{$series_refunded_payments->count()}})</a></li>

    <li><a href="#all" role="tab" data-toggle="tab"><i class="fa fa-question-circle"></i> Toutes les transactions orphelines ({{$all_transactions->count()}})</a></li>

  </ul>



@stop