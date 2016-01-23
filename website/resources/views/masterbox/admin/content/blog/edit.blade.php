@extends('masterbox.layouts.admin')


@section('content')
  
  <div
    id="gotham"
    data-form-errors="{{ $errors->has() }}"
  ></div>

  @include('masterbox.admin.partials.navbar_content')
  
  <div class="row">
    <div class="grid-8">
      <h1 class="title title__section">Blog</h1>
      <h2 class="title title__subsection">Edition article {{ $blog_article->title }}</h2>
    </div>
    <div class="grid-4">
      <div class="+text-right">
        <a href="{{ action('MasterBox\Admin\ContentController@getNewBlog') }}" class="button button__section"><i class="fa fa-list"></i> Voir les articles</a>
      </div>
    </div>
  </div>

  <div class="divider divider__section"></div>
  
  <div class="form">
    {!! Form::open(array('action' => 'MasterBox\Admin\ContentController@postEditBlog', 'files' => true)) !!}
    
    {!! Form::hidden("blog_article_id", $blog_article->id) !!}

    <div class="row">
      <div class="grid-4">
        {!! Form::label("title", "Titre", ['class' => 'form__label']) !!}
        {!! Form::text("title", Request::old("title") ? Request::old("title") : $blog_article->title, ['class' => 'form__input']) !!}
        {!! Html::checkError('title', $errors) !!}
      </div>
      
      <div class="grid-4">
        {!! Form::label("slug", "Référencement", ['class' => 'form__label']) !!}
        {!! Form::text("slug", Request::old("slug") ? Request::old("slug") : $blog_article->slug, ['class' => 'form__input']) !!}
        {!! Html::checkError('slug', $errors) !!}
      </div>
      
      <div class="grid-4">
        {!! Form::label("url", "Url (permalink)", ['class' => 'form__label']) !!}
        {!! Form::text("url", Request::old("url") ? Request::old("url") : $blog_article->url, ['class' => 'form__input']) !!}
        {!! Html::checkError('url', $errors) !!}
      </div>
    </div>

    {!! Form::label("content", "Contenu", ['class' => 'form__label']) !!}
    {!! Form::textarea("content", Request::old("content") ? Request::old("content") : $blog_article->content, ['class' => 'form__markdown js-markdown']) !!}
    {!! Html::checkError('content', $errors) !!}

    <img src="{{ Html::resizeImage('small', $blog_article->thumbnail->filename) }}" />

    {!! Form::label("thumbnail", "Image d'illustration", ['class' => 'form__label']) !!}
    {!! Form::file('thumbnail') !!}
    {!! Html::checkError('thumbnail', $errors) !!}


    {!! Form::submit("Editer l'article", ['class' => 'button button__submit']) !!}
    {!! Form::close() !!}
  </div>

@stop