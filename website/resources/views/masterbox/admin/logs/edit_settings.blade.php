@extends('masterbox.layouts.admin')

@section('navbar')
  @include('masterbox.admin.partials.navbar_logs')
@stop

@section('content')
  
  <div class="row">
    <div class="grid-8">
      <h1 class="title title__section">Logs &amp; Configuration</h1>
      <h3 class="title title__subsection">Prises de contact</h3>
    </div>
  </div>


  {!! Html::info('Ci-dessous vous pouvez configurer les adresses emails destinataires pour les différents services') !!}
  {!! Form::open(array('action' => 'MasterBox\Admin\LogsController@postEditSettings')) !!}

    <div class="panel panel__wrapper">
      <div class="panel__header">
        <h3 class="panel__title">Configuration</h3>
      </div>
      <div class="panel__content">

          {!! Form::label("tech_support", "Support technique", ['class' => 'form__label']) !!}
          {!! Form::text("tech_support", (Request::old('tech_support')) ? Request::old('tech_support') : $contact_setting->tech_support, ['class' => 'form__input']) !!}
          {!! Html::checkError('tech_support', $errors) !!}

          {!! Form::label("com_support", "Support commercial", ['class' => 'form__label']) !!}
          {!! Form::text("com_support", (Request::old('com_support')) ? Request::old('com_support') : $contact_setting->com_support, ['class' => 'form__input']) !!}
          {!! Html::checkError('com_support', $errors) !!}

          <div class="+spacer-small"></div>

          <button type="submit" class="button button__default --blue"><i class="fa fa-refresh"></i> Mettre à jour la configuration</button>
        
      </div>
    </div>

  {!! Form::close() !!}


@stop