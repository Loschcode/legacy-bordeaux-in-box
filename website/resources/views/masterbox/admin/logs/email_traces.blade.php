@extends('masterbox.layouts.admin')

@section('navbar')
@include('masterbox.admin.partials.navbar_logs')
@stop

@section('content')

<div class="row">
  <div class="grid-8">
    <h1 class="title title__section">Logs &amp; Configuration</h1>
    <h3 class="title title__subsection">Traces des emails</h3>
  </div>
</div>


<table class="js-datatable-simple">

  <thead>

    <tr>
      <th>ID</th>
      <th>Client</th>
      <th>Abonnement</th>
      <th>MailGun Message ID</th>
      <th>Email destinataire</th>
      <th>Sujet</th>
      <th>Préparé</th>
      <th>Envoyé</th>
      <th>Emails / images autorisés</th>
      <th>Première lecture</th>
      <th>Dernière lecture</th>
      <th>Action</th>
    </tr>

  </thead>

  <tbody>

    @foreach ($email_traces as $email_trace)

    <tr>
      <td>{{$email_trace->id}}</td>
      <td>

        @if ($email_trace->customer()->first() === NULL)
        N/A
        @else
        <a class="button button__default --green --table" href="{{ action('MasterBox\Admin\CustomersController@getFocus', ['id' => $email_trace->customer()->first()->id]) }}">{{$email_trace->customer()->first()->getFullName()}}</a>
        @endif

      </td>
      <td>

        @if ($email_trace->customer_profile()->first() !== NULL)

          <a class="button button__default --table {!! Html::getColorFromProfileStatus($email_trace->customer_profile()->first()->status)!!}" href="{{ action('MasterBox\Admin\ProfilesController@getFocus', ['id' => $email_trace->customer_profile()->first()->id])}}">


          #{{ $email_trace->customer_profile()->first()->id }} {!! Html::getReadableProfileStatus($email_trace->customer_profile()->first()->status) !!}

        </a><br/>

        @else
        N/A
        @endif

      </td>
      <td>{{$email_trace->mailgun_message_id}}</td>
      <td>{{$email_trace->recipient}}</td>
      <td>{{$email_trace->subject}}</td>
      <td>{{$email_trace->prepared_at}}</td>
      <td>{{$email_trace->delivered_at}}</td>
      <td>
        @if ($email_trace->customer_profile()->first() !== NULL)
          {{$email_trace->customer_profile()->first()->customer()->first()->emails_fully_autdorized}}
        @else
          N/A
        @endif
      </td>
      <td>{{$email_trace->first_opened_at}}</td>
      <td>{{$email_trace->last_opened_at}}</td>

      <td>      
        <a data-modal class="button button__default --green --table" href="{{ action('MasterBox\Admin\LogsController@getEmailTrace', ['id' => $email_trace->id]) }}"><i class="fa fa-search"></i></a>
        <a class="button button__default --red --table js-delete-confirm" href="{{url('admin/logs/delete-email-trace/'.$email_trace->id)}}"><i class="fa fa-trash"></i> </a>
      </td>
    </tr>

    @endforeach

  </tbody>

</table>

@stop