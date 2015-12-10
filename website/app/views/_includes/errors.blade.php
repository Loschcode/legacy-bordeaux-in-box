@if ($errors->has())
  <div class="remove spyro-alert spyro-alert-danger">
    <strong>Whoops !</strong> Il y'a des erreurs dans le formulaire <br/>
    <ul>
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>  
      @endforeach
    </ul>
  </div>
@endif