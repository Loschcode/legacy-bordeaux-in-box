@section('page')
  <i class="fa fa-bullhorn"></i> Edition partenaire #{{$partner->id}}
@stop

@section('buttons')

@if (URL::previous() != Request::root())
  
  <a href="{{URL::previous()}}#partners" class="spyro-btn spyro-btn-success">Retour</a>

@endif

@stop

@section('content')

  @if (Session::has('message'))
    <div class="js-alert-remove spyro-alert spyro-alert-success">{{ Session::get('message') }}</div>
  @endif

  @if ($errors->has())
    <div class="js-alert-remove spyro-alert spyro-alert-danger">Erreurs dans le formulaire</div>
  @endif

  {!! Form::open(['action' => 'AdminProductsController@postEditPartner', 'files' => true]) !!}

    {!! Form::hidden("partner_id", $partner->id) !!}

  <div class="form-group @if ($errors->first('name')) has-error has-feedback @endif">
    {!! Form::label("name", "Nom", ['class' => 'sr-only']) !!}
    {!! Form::text("name", (Input::old("name")) ? Input::old("name") : $partner->name, ['class' => 'form-control', 'placeholder' => 'Nom']) !!}
  </div>

  <div class="form-group @if ($errors->first('description')) has-error has-feedback @endif">
    {!! Form::label("description", "Description", ['class' => 'sr-only']) !!}
    {!! Form::textarea("description", (Input::old("description")) ? Input::old("descriptionr") : $partner->description, ['class' => 'form-control', 'placeholder' => 'Description']) !!}
  </div>

  <div class="form-group @if ($errors->first('website')) has-error has-feedback @endif">
    {!! Form::label("website", "Site web", ['class' => 'sr-only']) !!}
    {!! Form::text("website", (Input::old("website")) ? Input::old("websiter") : $partner->website, ['class' => 'form-control', 'placeholder' => 'Site web (http://www.monsite.fr)']) !!}
  </div>

  <div class="form-group @if ($errors->first('facebook')) has-error has-feedback @endif">
    {!! Form::label("facebook", "Facebook", ['class' => 'sr-only']) !!}
    {!! Form::text("facebook", (Input::old("facebook")) ? Input::old("facebookr") : $partner->facebook, ['class' => 'form-control', 'placeholder' => 'Facebook (http://www.facebook.com)']) !!}
  </div>

  <div class="form-group @if ($errors->first('blog_article_id')) has-error has-feedback @endif">
    {!! Form::label("blog_article_id", "Article du blog", ['class' => 'sr-only']) !!}
    {!! Form::select('blog_article_id', $blog_articles_list, $partner->blog_article_id) !!}

  </div>

  <!-- Image -->
  <div class="form-group @if ($errors->first('image')) has-error has-feedback @endif">

    @foreach ($partner->images()->get() as $image)

      <img width="100" src="{{ $image->getImageUrl() }}">
      {{ Form::checkbox('edit_images['.$image->id.']', $image->id, "checked=checked") }}

    @endforeach

    {!! Form::file('images[0]') !!}
    {!! Form::file('images[1]') !!}
    {!! Form::file('images[2]') !!}

  </div>

  {!! Form::submit("Mettre à jour ce partenaire", ['class' => 'spyro-btn spyro-btn-success']) !!}

  {!! Form::close() !!}


@stop