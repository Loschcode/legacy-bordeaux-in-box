
      <div class="spyro-well">

         {!! Form::open(array('action' => 'MasterBox\Admin\ProfilesController@postEditQuestions')) !!}

         {!! Form::hidden('customer_profile_id', $profile->id) !!}

         @foreach ($questions as $question)

          <div class="form-group">

             <label>{{$question->question}}</label>
              <hr>
             <?php $answers = $profile->answers(); ?>

             <?php $old_reply = $answers->where('box_question_id', $question->id); ?>

             @if (($question->type === "text") || ($question->type === 'date') || ($question->type === 'member_email'))

               {!! Form::text($question->id.'-0', ($old_reply->first() != NULL) ? $old_reply->first()->answer : Request::old($question->id), ['class' => 'form-control']) !!}

             @elseif ($question->type === "textarea")

               {!! Form::textarea($question->id.'-0', ($old_reply->first() != NULL) ? $old_reply->first()->answer : Request::old($question->id), ['class' => 'form-control']) !!}


             @else

               @if ($question->answers()->first() == NULL)

                 Aucune

               @endif

               @foreach ($question->answers()->get() as $answer)

               <?php $answers = $profile->answers(); ?>
               <?php $old_reply = $answers->where('box_question_id', $question->id); ?>

                 @if ($question->type === 'radiobutton')


                   @if ($old_reply->first() != NULL)
                   {!! Form::radio($question->id.'-0', $answer->content, ($old_reply->first()->answer == $answer->content) ? true : Request::old($question->id.'-0'), array('id' => $answer->id)) !!}
                   @else
                     {!! Form::radio($question->id.'-0', $answer->content, '', array('id' => $answer->id)) !!}
                   @endif
                   {!! Form::label($answer->id, $answer->content) !!}

                   <br/>

                 @elseif ($question->type == 'checkbox')

                   @if ($old_reply === NULL)

                     {!! Form::checkbox($question->id.'-'.$answer->id, $answer->content, Request::old($question->id.'-'.$answer->id), array('id' => $answer->id)) !!}

                   @else

                     {!! Form::checkbox($question->id.'-'.$answer->id, $answer->content, ($old_reply->where('answer', $answer->content)->first()) ? true : Request::old($question->id.'-'.$answer->id), array('id' => $answer->id)) !!}

                   @endif
                   {!! Form::label($answer->id, $answer->content) !!}

                   <br/>


                 @endif

               @endforeach

             @endif

             @if ($errors->first($question->id.'-0'))
              <span class="spyro-text-danger"><i class="fa fa-times"></i> {{{ $errors->first($question->id.'-0') }}}</span>
             @endif

            </div>

            <div class="spacer20"></div>

         @endforeach

         {!! Form::submit("Valider", ['class' => 'spyro-btn spyro-btn-lg spyro-btn-success']) !!}

         {!! Form::close() !!}

      </div>
