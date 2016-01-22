@extends('masterbox.layouts.admin')

@section('page')
  
  <div class="navbar">
    <div class="navbar__wrapper">
      <ul class="navbar__list">
        <li class="navbar__item">
          <a class="navbar__link" href="{{ action('MasterBox\Admin\ContentController@getBlog') }}">Blog</a>
        </li>
        <li class="navbar__item">
          <a class="navbar__link" href="{{ action('MasterBox\Admin\ContentController@getIllustrations') }}">Illustrations</a>
        </li>
        <li class="navbar__item">
          <a class="navbar__link" href="{{ action('MasterBox\Admin\ContentController@getPages') }}">Pages</a>
        </li>
      </ul>
    </div>
  </div>
  
  <h1 class="title title__section">Blog</h1>


  <table class="js-datatable-simple">

    <thead>
      <tr>
        <th>Titre</th>
        <th>Date</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($blog_articles as $blog_article)

      <tr>
        <td>{{$blog_article->title}}</td>
        <td>{{ Html::dateFrench($blog_article->created_at) }}</td>
        <td>
          <a class="button__table --first" href="{{url('/admin/content/edit-blog/'.$blog_article->id)}}"><i class="fa fa-pencil"></i></a>
          <a class="js-confirm-delete button__table --last" href="{{url('/admin/content/delete-blog/'.$blog_article->id)}}"><i class="fa fa-trash-o"></i></a>
        </td>
      </tr>

      @endforeach
    </tbody>

  </table>

@stop