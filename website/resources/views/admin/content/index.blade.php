@extends('layouts.admin')

@section('page')
	<i class="fa fa-picture-o"></i> Contenus
@stop

@section('buttons')
	<a class="spyro-btn spyro-btn-success" href="{{url('/admin/content/new-blog')}}"><i class="fa fa-plus"></i> BLOG</a>
  <a class="spyro-btn spyro-btn-success" href="{{url('/admin/content/new-illustration')}}"><i class="fa fa-plus"></i> ILLUSTRATION</a>
@stop

@section('content')

	@if (session()->has('message'))
		<div class="js-alert-remove spyro-alert spyro-alert-success">{{ session()->get('message') }}</div>
  	@endif

  <!-- Tabs -->
  <ul class="nav nav-tabs" role="tablist">

    <li class="active"><a href="#blog" role="tab" data-toggle="tab"><i class="fa fa-bullhorn"></i> Blog</a></li>
    <li><a href="#illustrations" role="tab" data-toggle="tab"><i class="fa fa-picture-o"></i> Illustrations</a></li>
    <li><a href="#pages" role="tab" data-toggle="tab"><i class="fa fa-file-o"></i> Pages</a></li>

  </ul>

  <div class="tab-content">

    <!-- Tab List -->
    <div class="tab-pane active" id="blog">

  	<table class="js-datas">

  		<thead>
  			<tr>
  				<th>Thumbnail</th>
  				<th>Titre</th>
  				<th>Slug</th>
  				<th>Url</th>
  				<th>Date</th>
  				<th>Action</th>
  			</tr>
  		</thead>
  		<tbody>
			@foreach ($blog_articles as $blog_article)

				<tr>

					<th><img width="100" src="{{ url($blog_article->thumbnail->full)}}"></th>
					<th>{{$blog_article->title}}</th>

					<th>{{$blog_article->slug}}</th>
					<th>{{$blog_article->url}}</th>
					<th>{{$blog_article->created_at}}</th>
					<th>
						<a data-toggle="tooltip" title="Editer" class="spyro-btn spyro-btn-sm spyro-btn-warning" href="{{url('/admin/content/edit-blog/'.$blog_article->id)}}"><i class="fa fa-pencil"></i></a>
						<a data-toggle="tooltip" title="Supprimer" class="spyro-btn spyro-btn-sm spyro-btn-danger" href="{{url('/admin/content/delete-blog/'.$blog_article->id)}}"><i class="fa fa-trash-o"></i></a>
					</th>

				</tr>

			@endforeach
		</tbody>

	</table>

  </div>

  <!-- Tab List -->
  <div class="tab-pane" id="illustrations">

  <table class="js-datas">
    <thead>
      <tr>
        <th>Thumbnail</th>
        <th>Titre</th>
        <th>Slug</th>
        <th>Description</th>
        <th>Date</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>

      @foreach ($image_articles as $image_article)

        <tr>
          <td><img width="150" src="{{ url($image_article->image->full)}}"></td>
          <td>{{$image_article->title}}</td>
          <td>{{$image_article->slug}}</td>
          <td>{{$image_article->description}}</td>
          <td>{!! Html::diffHumans($image_article->created_at) !!}</td>
          <td>
            <a data-toggle="tooltip" title="Editer" class="spyro-btn spyro-btn-warning spyro-btn-sm" href="{{url('/admin/content/edit-illustration/'.$image_article->id)}}"><i class="fa fa-pencil"></i></a>
            <a data-toggle="tooltip" title="Supprimer" class="spyro-btn spyro-btn-danger spyro-btn-sm" href="{{url('/admin/content/delete-illustration/'.$image_article->id)}}"><i class="fa fa-times"></i></a>
          </td>

        </tr>

      @endforeach
    </tbody>
  </table>

  </div>

  <!-- Tab List -->
  <div class="tab-pane" id="pages">

  {!! Form::open(array('action' => 'AdminContentController@postEditPage')) !!}

  @foreach ($pages as $page)

      <div class="form-group @if ($errors->first($page->slug)) has-error has-feedback @endif">

        {!! Form::label($page->slug, $page->title, ['class' => 'control-label']) !!}
        {!! Form::textarea($page->slug, $page->content, ['class' => 'form-control js-summernote']) !!}

        @if ($errors->first($page->slug))
          <span class="glyphicon glyphicon-remove form-control-feedback"></span>
          <span class="help-block">{{ $errors->first($page->slug) }}</span>
        @endif
      </div>

  @endforeach

    {!! Form::submit("Enregistrer les modifications", ['class' => 'spyro-btn spyro-btn-success spyro-btn-lg']) !!}
    <br /><br />

  {!! Form::close() !!}

  </div>

  </div>

@stop