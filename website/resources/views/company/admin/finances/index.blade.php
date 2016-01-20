@extends('company.layouts.admin')

@section('page')
  <i class="fa fa-calculator"></i> Finances
@stop

@section('content')
  @if (session()->has('message'))
    <div class="js-alert-remove spyro-alert spyro-alert-success">{{ session()->get('message') }}</div>
  @endif

  @if ($errors->has())
    <div class="js-alert-remove spyro-alert spyro-alert-danger">Erreurs dans le formulaire</div>
  @endif

  {!! Html::info('Rapport des factures et différents chiffres clés.') !!}


  <ul class="nav nav-tabs" role="tablist">

    <li class="active"><a href="#series" role="tab" data-toggle="tab"><i class="fa fa-cube"></i>Factures &amp; Fiscalité</a></li>
    <li><a href="#others" role="tab" data-toggle="tab"><i class="fa fa-star"></i> Autre</a></li>

  </ul>

  <a href="{{ action('Company\Admin\FinancesController@getFinancesSpreadsheetTotalCredits') }}">Spreadsheet crédits total</a> -
    <a href="{{ action('Company\Admin\FinancesController@getFinancesSpreadsheetTotalCredits', ['only_fees' => TRUE]) }}">Spreadsheet crédits frais Stripe total</a>
  <br />

  <a href="{{ action('Company\Admin\FinancesController@getFinancesSpreadsheetTotalDebits') }}">Spreadsheet débits total</a> - <a href="{{ action('Company\Admin\FinancesController@getFinancesSpreadsheetTotalDebits', ['only_fees' => TRUE]) }}">Spreadsheet débits remboursement frais Stripe total</a>

  <div class="tab-content">

    <!-- Tab List -->
    <div class="tab-pane active" id="series">

      <table class="js-datas">

        <thead>

          <tr>
            <th>ID</th>
            <th>Série</th>
            <th>Chiffres</th>
            <th>Téléchargements</th>
          </tr>

        </thead>

        <tbody>

          @foreach ($series as $serie)

            <tr>

              <th>{{$serie->id}}</th>
              <th>{{$serie->delivery}}</th>
              <th>{{$serie->orders()->sum('already_paid')}} €</th>

              <th>
              <a href="{{ url('/admin/taxes/bills/' . $serie->id)}}">Factures</a> - <a href="{{ url('/admin/taxes/payments/' . $serie->id)}}">Paiements</a>
              </th>

            </tr>

          @endforeach

          </tbody>

        </table>
    </div>

@stop