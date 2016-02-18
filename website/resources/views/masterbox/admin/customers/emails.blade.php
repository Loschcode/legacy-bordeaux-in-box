@extends('masterbox.layouts.admin')

@section('navbar')
  @include('masterbox.admin.partials.navbar_customers')
@stop

@section('content')

<div class="row">
  <div class="grid-8">
    <h1 class="title title__section">Emails</h1>
  </div>
</div>

<div class="divider divider__section"></div>

<a href="{{ action('MasterBox\Admin\CustomersController@getEmails') }}" class="button button__default --blue">Tout les clients</a>
<a href="{{ action('MasterBox\Admin\CustomersController@getEmails', ['sort' => 'having-a-profile-subscribed']) }}" class="button button__default --blue">Clients avec un abonnement en cours</a>
<a href="{{ action('MasterBox\Admin\CustomersController@getEmails', ['sort' => 'never-bought-a-box']) }}" class="button button__default --blue">Clients n'ayant jamais commandés</a>
<a href="{{ action('MasterBox\Admin\CustomersController@getEmails', ['sort' => 'bought-a-box-but-stop']) }}" class="button button__default --blue">Clients ayant déjà commandés une box mais qui ont stop</a>


<div class="+spacer-extra-small"></div>

<div class="panel">
  <div class="panel__wrapper">
    <div class="panel__header">
      <h3 class="panel__title">{{ $title }} ({{ count($emails) }})</h3>
    </div>
    <div class="panel__content">
      
      @foreach ($emails as $email)
        {{ $email }},
      @endforeach

    </div>
  </div>
</div>


@stop
