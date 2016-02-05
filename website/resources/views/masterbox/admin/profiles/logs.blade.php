@extends('masterbox.layouts.admin')

@section('navbar')
  @include('masterbox.admin.partials.navbar_profiles')
@stop

@section('gotham')
  {!! Html::gotham([
    'controller' => 'masterbox.admin.profiles.payments'
  ]) !!}
@stop

@section('navbar')
  @include('masterbox.admin.partials.navbar_profiles')
@stop

@section('content')
      
  <div class="row">
    <div class="grid-8">
      <h1 class="title title__section">Abonnement #{{ $profile->id }}

    @if ($profile->status === 'expired')
    ({!! Html::getReadableProfileStatus($profile->status) !!})
    @endif
    
      </h1>
      <h2 class="title title__subsection">Logs</h2>
    </div>
  </div>
  <div class="divider divider__section"></div>


  <table>

    <thead>

      <tr>
        <th>ID</th>
        <th>Log</th>
        <th>Metadata</th>
        <th>Gestion</th>
        <th>Date</th>
        <th>Action</th>
      </tr>

    </thead>

    <tbody>

      @foreach ($logs as $log)

        <tr>

          <th>{{$log->id}}</th>
          <th>{{$log->log}}</th>
          <th>
          @foreach ($log->metadata as $label => $data)
          ({{$label}} : {{$data}}) 
          @endforeach
          </th>
          <th>{{$log->administrator()->first()->getFullName()}}</th>
          <th>{{ Html::diffHumans($log->created_at) }}</th>
          <th>
          </th>

        </tr>

      @endforeach

      </tbody>

    </table>




@stop