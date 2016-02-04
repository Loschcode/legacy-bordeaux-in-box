@extends('masterbox.layouts.email')

@section('title')
Notification
@stop

@section('content')
  {!! Html::emailLine($content) !!}
@stop