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

<div class="panel">
  <div class="panel__wrapper">
    <div class="panel__header">
      <h3 class="panel__title">Listing de tout les emails clients</h3>
    </div>
    <div class="panel__content">
      
      @foreach ($emails as $email)
        {{ $email }},
      @endforeach

    </div>
  </div>
</div>


@stop
