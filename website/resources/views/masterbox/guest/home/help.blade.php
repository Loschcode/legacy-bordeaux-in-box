@extends('masterbox.layouts.master')

@section('content')

<div class="section section__wrapper">
  <h1 class="section__title --page">Besoin d'aide ?</h1>
</div>

<div class="page page__wrapper">
  <div class="container">
    <div class="grid-10 grid-centered">
      <div class="typography">
        {!! Markdown::convertToHtml($help->content) !!}
      </div>
    </div>
  </div>
</div>

@stop