@extends('sandbox.layouts.ui')
@section('content')

  <div class="container">
    <div class="grid-8 grid-centered">
      
      <?php /*
      <!-- Alert component -->
      <h2>Alert Component</h2>
      <div class="alert">Default alert</div>

      <h3>Option color</h3>
      @foreach ($colors as $color)
        <div class="alert --{{$color}}">{{ ucfirst($color) }} alert</div>
      @endforeach

      <!-- Badge component -->
      <h2>Bagdge component</h2>

      <span class="badge">1 new message</span>

      <h3>Option color</h3>
      @foreach ($colors as $color)
        <span class="badge --{{$color}}">1 new message</span>
      @endforeach

      <h3>Option Number</h3>
      @for($i = 0; $i < 10; $i++)
        <span class="badge --number">{{ $i }}</span>
      @endfor

      <!-- Breadcrumb component -->
      <h2>Breadcrumb Component</h2>
      <ul class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li><a href="#">Blog</a></li>
        <li><a href="#">Category</a></li>
        <li><span>Post</span></li>
      </ul>

      */ ?>
      
      <h2>Button Component</h2>
      <a class="button">Ajouter à ma liste</a>
      <a class="button --red">Supprimer de ma liste</a>
      <a class="button --green">Ajouter l'utilisateur</a>
      <a class="button --yellow">Modifier</a>

    </div>
  </div>

@stop
