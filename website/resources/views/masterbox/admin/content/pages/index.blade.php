@extends('masterbox.layouts.admin')

@section('navbar')
  @include('masterbox.admin.partials.navbar_content')
@stop

@section('content')
  
  <div class="row">
    <div class="grid-8">
      <h1 class="title title__section">Pages</h1>
      <h2 class="title title__subsection">Les pages du site</h2>
    </div>
  </div>

  <div class="divider divider__section"></div>


  <table class="js-datatable-simple">

    <thead>
      <tr>
        <th>Page</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($pages as $page)

      <tr>
        <td>{{ $page->title }}</td>
        <td>
          <a class="button button__default --blue --table" href="{{ action('MasterBox\Admin\ContentController@getEditPage', ['id' => $page->id]) }}"><i class="fa fa-pencil"></i></a>
        </td>
      </tr>

      @endforeach
    </tbody>

  </table>

@stop
