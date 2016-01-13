@extends('masterbox.layouts.admin')
@section('page')
  <i class="fa fa-music"></i> Bip
@stop

@section('buttons')

@stop

@section('content')

  {!! Html::info("Laissez cette page ouverte, lors d'une nouvelle commande une petite musique sera joué") !!}
  
  <div id="js-page-bip"></div>
  <div id="counter" class="spyro-well text-center"><h1>0</h1></div>

  <audio id="tada">
    <source src="{{ url('public/sounds/tada.mp3') }}"></source>
  </audio>

@stop
