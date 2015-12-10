@section('page')
  <i class="fa fa-gift"></i> Boxes
@stop

@section('buttons')

@if (URL::previous() != Request::root())
  
  <a href="{{URL::previous()}}" class="spyro-btn spyro-btn-success">Retour</a>

@endif

@stop

@section('content')

  @if (Session::has('message'))
    <div class="js-alert-remove spyro-alert spyro-alert-success">{{ Session::get('message') }}</div>
  @endif

  @if ($errors->has())
    <div class="js-alert-remove spyro-alert spyro-alert-danger">Erreurs dans le formulaire</div>
  @endif

  {!! HTML::info("L'édition d'une box la désactive par défaut") !!}

  <div class="w80">
    {!! Form::open(array('action' => 'AdminBoxesController@postEdit', 'files' => true)) !!}
    
    {!! Form::hidden('box_id', $box->id) !!}


    <!-- Title -->
    <div class="form-group @if ($errors->first('title')) has-error has-feedback @endif">
      {!! Form::label("title", "Titre", ['class' => 'control-label']) !!}
      {!! Form::text("title", (Input::old("title")) ? Input::old("title") : $box->title, ['class' => 'form-control']) !!}

      @if ($errors->first('title'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('title') }}</span>
      @endif
    </div>

    <!-- Description -->
    <div class="form-group @if ($errors->first('description')) has-error has-feedback @endif">
      {!! Form::label("description", "Description", ['class' => 'control-label']) !!}
      {!! Form::text("description", (Input::old("description")) ? Input::old("description") : $box->description, ['class' => 'form-control']) !!}

      @if ($errors->first('description'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('description') }}</span>
      @endif
    </div>

    <!-- Image -->
    <div class="form-group @if ($errors->first('image')) has-error has-feedback @endif">
      {!! Form::label("image", "Image", ['class' => 'control-label']) !!}
      {!! Form::file('image', ['class' => 'form-control']) !!}

      @if ($errors->first('image'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('image') }}</span>
      @else
        <span class="help-block">Laisser vide si vous ne voulez pas la changer</span>
      @endif
    </div>

    {!! Form::submit("Mettre à jour cette box", ['class' => 'spyro-btn spyro-btn-success spyro-btn-lg']) !!}

    {!! Form::close() !!}
  </div>

@stop
