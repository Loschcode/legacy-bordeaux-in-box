@extends('masterbox.layouts.master')

@section('content')


<div class="page page__wrapper">
  <div class="container">
    <div class="grid-10 grid-centered">
      <div class="typography +text-center">
        {!! Markdown::convertToHtml($legal->content) !!}
      </div>
    </div>
  </div>
</div>

@stop