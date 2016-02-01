@extends('masterbox.layouts.admin')

@section('content')

  @include('masterbox.admin.partials.navbar_content')

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
        <th>Titre</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($image_articles as $image_article)

      <tr>
        <td>{{ $image_article->title }}</td>
        <td>
          <a class="button__table" href="{{ action('MasterBox\Admin\ContentController@getEditIllustration', ['id' => $image_article->id]) }}"><i class="fa fa-pencil"></i></a>
          <a class="button__table js-confirm-delete" href="{{ action('MasterBox\Admin\ContentController@getDeleteIllustration', ['id' => $image_article->id]) }}"><i class="fa fa-trash-o"></i></a>

        </td>
      </tr>

      @endforeach
    </tbody>

  </table>

@stop
