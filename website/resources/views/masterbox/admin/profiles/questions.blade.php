@extends('masterbox.layouts.admin')

@section('navbar')
  @include('masterbox.admin.partials.navbar_profiles')
@stop

@section('content')
  
  @include('masterbox.admin.partials.navbar_profiles')


  <div class="row">
    <div class="grid-8">
      <h1 class="title title__section">Abonnement #{{ $profile->id }}</h1>
      <h2 class="title title__subsection">Questionnaire</h2>
    </div>
    <div class="grid-4">
      <div class="+text-right">
        <a href="{{ action('MasterBox\Admin\ProfilesController@getIndex') }}" class="button button__section"><i class="fa fa-list"></i> Voir les abonnements</a>
      </div>
    </div>
  </div>
  
  <div class="divider divider__section"></div>

  <div class="labelauty-default-small">
     {!! Form::open(array('action' => 'MasterBox\Admin\ProfilesController@postEditQuestions')) !!}

     {!! Form::hidden('customer_profile_id', $profile->id) !!}

     @foreach ($questions as $question)


         <label class="form__label">{{$question->question}}</label>

         <?php $answers = $profile->answers(); ?>

         <?php $old_reply = $answers->where('box_question_id', $question->id); ?>

         @if (in_array($question->type, ['text', 'date', 'member_email']))

           {!! Form::text($question->id.'-0', ($old_reply->first() != NULL) ? $old_reply->first()->answer : Request::old($question->id), ['class' => 'form__input']) !!}

         @elseif ($question->type === "textarea")

           {!! Form::textarea($question->id.'-0', ($old_reply->first() != NULL) ? $old_reply->first()->answer : Request::old($question->id), ['class' => 'form__input']) !!}


         @else

           @if ($question->answers()->first() == NULL)

             Aucune

           @endif

           @foreach ($question->answers()->get() as $answer)

           <?php $answers = $profile->answers(); ?>
           <?php $old_reply = $answers->where('box_question_id', $question->id); ?>

             @if ($question->type === 'radiobutton')


               @if ($old_reply->first() != NULL)
               {!! Form::radio($question->id.'-0', $answer->content, ($old_reply->first()->answer == $answer->content) ? true : Request::old($question->id.'-0'), ['id' => $answer->id, 'data-labelauty' => $answer->content]) !!}
               @else
                 {!! Form::radio($question->id.'-0', $answer->content, '', ['id' => $answer->id, 'data-labelauty' => $answer->content]) !!}
               @endif

             @elseif ($question->type == 'checkbox')

               @if ($old_reply === NULL)

                 {!! Form::checkbox($question->id.'-'.$answer->id, $answer->content, Request::old($question->id.'-'.$answer->id), ['id' => $answer->id, 'data-labelauty' => $answer->content]) !!}

               @else

                 {!! Form::checkbox($question->id.'-'.$answer->id, $answer->content, ($old_reply->where('answer', $answer->content)->first()) ? true : Request::old($question->id.'-'.$answer->id), ['id' => $answer->id, 'data-labelauty' => $answer->content]) !!}

               @endif


             @endif

           @endforeach

         @endif
          
          {{ Html::checkError($question->id . '-0', $errors) }}


      <div class="+spacer-small"></div>

     @endforeach


     {!! Form::submit("Valider", ['class' => 'button button__submit']) !!}

     {!! Form::close() !!}
  </div>

@stop