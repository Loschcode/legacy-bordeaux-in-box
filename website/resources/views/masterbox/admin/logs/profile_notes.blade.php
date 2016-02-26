@extends('masterbox.layouts.admin')

@section('navbar')
@include('masterbox.admin.partials.navbar_logs')
@stop

@section('content')

<div class="row">
  <div class="grid-8">
    <h1 class="title title__section">Logs &amp; Configuration</h1>
    <h3 class="title title__subsection">Note des abonnements</h3>
  </div>
</div>



<table class="js-datatable-simple">

  <thead>

    <tr>
      <th>ID</th>
      <th>Client</th>
      <th>Abonnement</th>
      <th>Auteur de la note</th>
      <th>Note</th>
      <th>Date</th>
    </tr>

  </thead>

  <tbody>

    @foreach ($profile_notes as $profile_note)

    <tr>
      <th>{{$profile_note->id}}</th>
      <th>

        @if ($profile_note->customer_profile()->first() === NULL)
        N/A
        @else
        <a href="{{ action('MasterBox\Admin\CustomersController@getFocus', ['id' => $profile_note->customer_profile()->first()->customer()->first()->id]) }}">{{$profile_note->customer_profile()->first()->customer()->first()->getFullName()}}</a>
        @endif

      </th>
      <th>

        @if ($profile_note->customer_profile()->first() !== NULL)

        <a class="button button__default --table {{HTML::getColorFromProfileStatus($profile_note->customer_profile()->first()->status)}}" href="{{action('MasterBox\Admin\ProfilesController@getFocus', ['id' => $profile_note->customer_profile()->first()->id])}}">


          #{{ $profile_note->customer_profile()->first()->id }} {!! Html::getReadableProfileStatus($profile_note->customer_profile()->first()->status) !!}

        </a><br/>

        @else
        N/A
        @endif

      </th>
      <th>

      @if ($profile_note->customer_profile()->first() === NULL)
        N/A
        @else
        <a href="{{ action('MasterBox\Admin\CustomersController@getFocus', ['id' => $profile_note->customer_profile()->first()->id]) }}">{{$profile_note->customer_profile()->first()->firstName}}</a>
        @endif

      </th>

      <th>{{$profile_note->note}}</th>

      <th>{{$profile_note->created_at}}</th>

    </tr>

    @endforeach

  </tbody>

</table>

@stop