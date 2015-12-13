@extends('layouts.master')
@section('content')

  <div class="page">
    <div class="container">
      <h1 class="page-title">Conditions Générales de Vente</h1>
      <div class="description">{{ nl2br($cgv->content) }}</div>
    </div>
  </div>

  <div class="spacer150"></div>
  </div>

  @include('_includes.footer')
@stop
