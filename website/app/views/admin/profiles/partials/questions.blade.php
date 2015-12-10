
      <div class="spyro-well">

       @if ($box == NULL)

         Aucune box sélectionnée

       @else

         {{ Form::open(array('url' => '/admin/profiles/edit-questions')) }}

         {{ Form::hidden('user_profile_id', $profile->id)}}
         {{ Form::hidden('box_id', $box->id)}}

         @foreach ($questions as $question)

          <div class="form-group">

             <label>{{$question->question}}</label>
              <hr>
             <?php $answers = $profile->answers(); ?>

             <?php $old_reply = $answers->where('box_question_id', $question->id); ?>

             @if (($question->type === "text") || ($question->type === 'date') || ($question->type === 'member_email'))

               {{ Form::text($question->id.'-0', ($old_reply->first() != NULL) ? $old_reply->first()->answer : Input::old($question->id), ['class' => 'form-control']) }}

             @elseif ($question->type === "textarea")

               {{ Form::textarea($question->id.'-0', ($old_reply->first() != NULL) ? $old_reply->first()->answer : Input::old($question->id), ['class' => 'form-control']) }}


              @elseif ($question->type == "children_details")

                <!-- We will use it through all the inputs -->
                <?php $old_replies_names = $question->user_answers()->where('to_referent_slug', '=', 'child_name')->where('user_profile_id', '=', $profile->id)->get() ?>

                @if (count($old_replies_names) == 0)

                  <div data-name="empty-children" data-question="{{ $question->id }}">
                  </div>

                @endif

                @for ($i = 0; $i <= 10; $i++)

                  <?php

                  if (isset($old_replies_names[$i])) $old_replies_name = $old_replies_names[$i];
                  else $old_replies_name = NULL;

                  ?>

                  <div data-name="children" data-question="{{ $question->id }}" class="hidden">

                    <div class="row">
                      <div class="col-md-3">
                        {{ Form::text($question->id.'-0['.$i.'][child_name]',

                        (isset($old_replies_names[$i])) ? $old_replies_names[$i]->answer :
                        Input::old($question->id), ['placeholder' => 'Prénom', 'class' => 'form-control'])

                        }}
                      </div>

                      <?php

                      if ($old_replies_name !== NULL) $old_replies_sex = $question->user_answers()->where('to_referent_slug', '=', 'child_sex')->where('referent_id', '=', $old_replies_name->id)->first();
                      else $old_replies_sex = NULL;

                      ?>

                      <div class="col-md-3">
                        {{ Form::select($question->id.'-0['.$i.'][child_sex]', generate_children_sex(),

                        ($old_replies_sex !== NULL) ? $old_replies_sex->answer :
                        Input::old($question->id), ['class' => 'form-control'])

                        }}
                      </div>

                      <?php

                      if ($old_replies_name !== NULL) $old_replies_year = $question->user_answers()->where('to_referent_slug', '=', 'child_year')->where('referent_id', '=', $old_replies_name->id)->first();
                      else $old_replies_year = NULL;

                      ?>

                      <div class="col-md-2">
                        {{ Form::select($question->id.'-0['.$i.'][child_year]', generate_children_birth_form(),

                        ($old_replies_year !== NULL) ? $old_replies_year->answer :
                        Input::old($question->id), ['class' => 'form-control'])

                        }}
                      </div>

                      <?php

                      if ($old_replies_name !== NULL) $old_replies_month = $question->user_answers()->where('to_referent_slug', '=', 'child_month')->where('referent_id', '=', $old_replies_name->id)->first();
                      else $old_replies_month = NULL;

                      ?>

                      <div class="col-md-2">
                        {{ Form::select($question->id.'-0['.$i.'][child_month]', generate_month_form(),

                        ($old_replies_month !== NULL) ? $old_replies_month->answer :
                        Input::old($question->id), ['class' => 'form-control'])

                        }}
                      </div>

                      @if ($i >= 1)
                        <div class="col-md-2">
                          <a href="#" id="remove-children" class="spyro-btn spyro-btn-danger">Enlever cet enfant</a>
                        </div>
                      @endif
                    </div>
                    <div class="spacer10"></div>
                  </div>

                @endfor

                <div class="spacer20"></div>
                <a id="add-children" href="#" class="spyro-btn spyro-btn-primary no-loader">Un de plus ?</a>

                @if ($profile->getAnswer('children_old_names', TRUE))

                  <br /><br />
                  {{HTML::info("Anciennes entrées enfants : ".$profile->getAnswer('children_old_names', TRUE)." / ".$profile->getAnswer('children_old_ages', TRUE))}}

                @endif

             @else

               @if ($question->answers()->first() == NULL)

                 Aucune

               @endif

               @foreach ($question->answers()->get() as $answer)

               <?php $answers = $profile->answers(); ?>
               <?php $old_reply = $answers->where('box_question_id', $question->id); ?>

                 @if ($question->type === 'radiobutton')


                   @if ($old_reply->first() != NULL)
                   {{ Form::radio($question->id.'-0', $answer->content, ($old_reply->first()->answer == $answer->content) ? true : Input::old($question->id.'-0'), array('id' => $answer->id)) }}
                   @else
                     {{ Form::radio($question->id.'-0', $answer->content, '', array('id' => $answer->id)) }}
                   @endif
                   {{ Form::label($answer->id, $answer->content)}}

                   <br/>

                 @elseif ($question->type == 'checkbox')

                   @if ($old_reply === NULL)

                     {{ Form::checkbox($question->id.'-'.$answer->id, $answer->content, Input::old($question->id.'-'.$answer->id), array('id' => $answer->id)) }}

                   @else

                     {{ Form::checkbox($question->id.'-'.$answer->id, $answer->content, ($old_reply->where('answer', $answer->content)->first()) ? true : Input::old($question->id.'-'.$answer->id), array('id' => $answer->id)) }}

                   @endif
                   {{ Form::label($answer->id, $answer->content) }}

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

         {{ Form::submit("Valider", ['class' => 'spyro-btn spyro-btn-lg spyro-btn-success']) }}

         {{ Form::close() }}

       @endif
      </div>
