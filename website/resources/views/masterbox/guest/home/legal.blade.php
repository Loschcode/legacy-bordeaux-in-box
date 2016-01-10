@extends('masterbox.layouts.master')
@section('content')
	
	<div class="page">
		<div class="container">
			<h1 class="page-title">Mention légales</h1>
			<div class="description">{!! nl2br($legal->content) !!}</div>
		</div>
	</div>

  <div class="spacer150"></div>
  </div>

  <div class="footer-container">
    @include('masterbox.partials.footer', ['stick' => true])
  </div>
@stop