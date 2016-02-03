@extends('masterbox.layouts.admin')

@section('navbar')
@include('masterbox.admin.partials.navbar_deliveries_focus')
@stop

@section('content')
<div class="row">
  <div class="grid-12">
    <h1 class="title title__section">Série {{ Html::dateFrench($series->delivery, true) }} (#{{$series->id}})</h1>
    <h3 class="title title__subsection">Emails</h3>
  </div>
</div>

<div class="divider divider__section"></div>


<div class="panel panel__wrapper">
  <div class="panel__header"><i class="fa fa-envelope"></i> Listing des emails de la série {{$series->title}}</div>
  <div class="panel__content">

    @foreach ($series_email_listing as $email)
      {{$email}}, 
    @endforeach

  </div>
</div>

<div class="+spacer-small"></div>

<div class="panel panel__wrapper">
  <div class="panel__header"><i class="fa fa-envelope"></i> Listing des emails des profils non terminés de la série {{$series->title}}</div>
  <div class="panel__content">

    @foreach ($series_unfinished_email_listing as $email)
      {{$email}}, 
    @endforeach

  </div>
</div>

</div>

@stop