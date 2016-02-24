@extends('masterbox.layouts.admin')

@section('navbar')
  @include('masterbox.admin.partials.navbar_content')
@stop

@section('content')

  <div class="row">
    <div class="grid-8">
      <h1 class="title title__section">Illustrations</h1>
    </div>
    <div class="grid-4">
      <div class="+text-right">
        <a href="{{ action('MasterBox\Admin\ContentController@getNewIllustration') }}" class="button button__section"><i class="fa fa-plus"></i> Nouvelle Illustration</a>
      </div>
    </div>
  </div>

  <div class="divider divider__section"></div>


  <table class="js-datatable-simple">

    <thead>
      <tr>
        <th></th>
        <th>Titre</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($image_articles as $image_article)

      <tr>
        <td><img height="70" src="{{ Html::resizeImage('small', $image_article->image->filename) }}" /></td>
        <td>{{ $image_article->title }}</td>
        <td>
          <a class="button button__default --blue --table" href="{{ action('MasterBox\Admin\ContentController@getEditIllustration', ['id' => $image_article->id]) }}"><i class="fa fa-pencil"></i></a>
          <a class="button button__default --red --table js-confirm-delete" href="{{ action('MasterBox\Admin\ContentController@getDeleteIllustration', ['id' => $image_article->id]) }}"><i class="fa fa-trash-o"></i></a>

        </td>
      </tr>

      @endforeach
    </tbody>

  </table>

@stop
