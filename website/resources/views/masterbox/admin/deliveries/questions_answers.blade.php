@extends('masterbox.layouts.admin')

@section('navbar')
@include('masterbox.admin.partials.navbar_deliveries_focus')
@stop

@section('content')
<div class="row">
  <div class="grid-12">
    <h1 class="title title__section">Série {{ Html::dateFrench($series->delivery, true) }} (#{{$series->id}})</h1>
    <h3 class="title title__subsection">Questionnaire</h3>
  </div>
</div>

<div class="divider divider__section"></div>

{!! Html::info("Statistiques détaillées des préférences pour la série via le questionnaire") !!}

<table class="js-datatable-simple">
  <thead>
    <tr>
      <th>Question</th>
        <th>Box</th>
    </tr>
  </thead>
  <tbody>

    @foreach ($box_questions as $box_question)

      <tr>
        <th><strong>{{$box_question->short_question}}</strong></th>

          <th>

            @if (isset($form_stats[$box_question->id]))

              @foreach ($form_stats[$box_question->id] as $answer => $hit)
                {{$answer}} - {{$hit}}<br />
              @endforeach

            @else

              N/A

            @endif

          </th>

      </tr>

    @endforeach

  </tbody>

</table>


@stop
     