@extends('masterbox.layouts.admin')

@section('navbar')
  @include('masterbox.admin.partials.navbar_content')
@stop

@section('content')

  <div class="row">
    <div class="grid-8">
      <h1 class="title title__section">Blog</h1>
      <h2 class="title title__subsection">Les articles</h2>
    </div>
    <div class="grid-4">
      <div class="+text-right">
        <a href="{{ action('MasterBox\Admin\ContentController@getNewBlog') }}" class="button button__section"><i class="fa fa-plus"></i> Nouveau Article</a>
      </div>
    </div>
  </div>

  <div class="divider divider__section"></div>


  <table class="js-datatable-simple">

    <thead>
      <tr>
        <th>Id</th>
        <th>Titre</th>
        <th>Auteur</th>
        <th>Date</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($blog_articles as $blog_article)

      <tr>
        <td>{{ $blog_article->id }}</td>
        <td>{{$blog_article->title}}</td>
        <td>{{$blog_article->administrator()->first()->getFullName()}}</td>
        <td>{{ Html::dateFrench($blog_article->created_at) }}</td>
        <td>
          <a class="button button__default --blue --table" href="{{ action('MasterBox\Admin\ContentController@getEditBlog', ['id' => $blog_article->id]) }}"><i class="fa fa-pencil"></i></a>
          <a class="js-confirm-delete button button__default --red --table" href="{{ action('MasterBox\Admin\ContentController@getDeleteBlog', ['id' => $blog_article->id]) }}"><i class="fa fa-trash-o"></i></a>
        </td>
      </tr>

      @endforeach
    </tbody>

  </table>

@stop