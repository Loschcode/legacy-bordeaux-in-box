@extends('masterbox.layouts.email')

@section('title')
Prise de contact
@stop

@section('content')
	{!! Html::emailLine('<strong>Email:</strong> ' . $contact_email) !!}
	{!! Html::emailLine('<strong>Service:</strong> ' . $contact_service) !!}
	{!! Html::emailLine('<strong>Message:</strong> ' . $contact_message) !!}
@stop
