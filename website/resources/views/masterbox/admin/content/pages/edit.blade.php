@extends('masterbox.layouts.admin')

@section('navbar')
  @include('masterbox.admin.partials.navbar_content')
@stop

@section('content')
    
  <div class="row">
    <div class="grid-8">
      <h1 class="title title__section">Pages</h1>
      <h2 class="title title__subsection">Edition de la page {{ $page->title }}</h2>
    </div>
    <div class="grid-4">
      <div class="+text-right">
        <a href="{{ action('MasterBox\Admin\ContentController@getPages') }}" class="button button__section"><i class="fa fa-list"></i> Voir les pages</a>
      </div>
    </div>
  </div>

  <div class="divider divider__section"></div>
  
  <div class="form">
    {!! Form::open(array('action' => 'MasterBox\Admin\ContentController@postEditPage')) !!}
    
    {!! Form::hidden("page_id", $page->id) !!}
    
    {!! Form::label('content', $page->title, ['class' => 'form__label']) !!}
    {!! Form::textarea('content', $page->content, ['class' => 'form__markdown js-markdown']) !!}

    {!! Form::submit("Editer la page", ['class' => 'button button__submit']) !!}
    {!! Form::close() !!}
  </div>

@stop
