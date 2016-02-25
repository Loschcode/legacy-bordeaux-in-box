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
      <div class="row">
        <div class="grid-10">
          <h3 class="panel__title">{{ $title }} ({{ count($emails) }})</h3>
        </div>
        <div class="grid-2">
          <div class="+text-right">
            <a href="{{ Request::url() . '?format=text' }}"class="button button__default --green">TEXT</a>
            <a href="{{ Request::url() . '?format=csv' }}"class="button button__default --green">CSV</a>
          </div>
        </div>
      </div>
    </div>
    <div class="panel__content">
      {!! Html::info('CTRL+A et CTRL+C dans le champ pour copier les emails') !!}
      @if ( ! Request::has('format') OR Request::input('format') === 'text')
        <textarea class="form__input">@foreach ($emails as $email){{ $email }}, @endforeach
        </textarea>
      @else
        {{-- &#13;&#10 = New line in a textarea --}}
        <textarea class="form__input">Email,&#13;&#10;@foreach ($emails as $email){{ $email }}&#13;&#10;@endforeach</textarea>
      @endif

    </div>
  </div>
</div>


@stop
