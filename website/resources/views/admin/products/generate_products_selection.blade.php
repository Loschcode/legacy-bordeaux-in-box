@extends('layouts.admin')

@section('page')
  <i class="fa fa-filter"></i> Séléction de produits pour la série {{$serie->delivery}}  (#{{$serie->id}})
@stop

@section('buttons')

  @if (URL::previous() != Request::root())
    <a href="{{URL::previous()}}#filters" class="spyro-btn spyro-btn-success">Retour</a>
  @endif

@stop

@section('content')

  @if (session()->has('message'))
    <div class="js-alert-remove spyro-alert spyro-alert-success">{{ session()->get('message') }}</div>
  @endif

  @if ($errors->has())
    <div class="js-alert-remove spyro-alert spyro-alert-danger">Erreurs dans le formulaire</div>
  @endif

  {!! Form::info('Voici le tracking de la génération de la sélection pour la série. Le tracking est utile pour analyser une anomalie quelconque lors du processus de création') !!}

  @foreach ($devlogs as $devlog)

    @if ($devlog['type'] === 'info')
      <font color='grey'>
    @elseif ($devlog['type'] === 'error')
      <font color='error'>
    @elseif ($devlog['type'] === 'success')
      <font color='green'>
    @elseif ($devlog['type'] === 'light')
      <font color='purple'>
    @elseif ($devlog['type'] === 'strong')
      <strong>
    @endif

    {{$devlog['data']}}

    @if ($devlog['type'] === 'strong')
      </strong>
    @else
      </font>
    @endif

    <br />

  @endforeach


@stop