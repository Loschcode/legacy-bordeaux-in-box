@extends('layouts.master')

@section('content')

  <div id="js-page-box-form"></div>

  @include('master-box.partials.pipeline', ['step' => 2])

  <div class="block-description text-center">
    <div class="container">
      <div class="col-md-8 col-md-offset-2">
        <h1 class="title-step">Personnalise ta box</h1>
        @if ($order_preference->gift == TRUE)
          <p>Pour que la box soit la plus personnalisée possible, réponds à ces quelques questions concernant ton amie !</p>
        @else
          <p>
            Pour que la box soit la plus personnalisée possible, répondez à ces quelques questions !
          </p>
        @endif
        <div class="spacer20"></div>
        <p>
        	<a href="{{ url('order/choose-frequency?skip_box_form=true') }}" class="spyro-btn spyro-btn-warning spyro-btn-lg icon-space">Je ne veux pas personnaliser la box <i class="fa fa-angle-right"></i></a>
        </p>
      </div>
    </div>
  </div>

  <div class="clearfix"></div>
  <div class="spacer50"></div>

  <div class="container">

    <div class="col-md-8 col-md-offset-2">

      {!! Form::open(['class' => 'form-component']) !!}

      {!! Form::hidden('box_id', $box->id) !!}

      @if (session()->has('message'))
        <div>{{ session()->get('message') }}</div>
      @endif

      <!-- Flag -->
      @if (session()->has('flag-box-form'))
        <div id="already-answered"></div>
      @endif

      @foreach ($questions as $question)

        <!-- To redirect directly there in case of error -->
        <a name="{{$question->id}}-0"></a>

        <div class="js-block-form panel-container">
  
              <h3 class="answer">{{$question->question}}</h3>

              <?php $answers = $profile->answers(); ?>
              <?php $old_reply = $answers->where('box_question_id', $question->id); ?>
              
              <!-- Errors -->
              @if (session()->has('flag-box-form'))

                @if ($errors->has($question->id . '-0'))
                  <i class="fa fa-times error"></i>
                @else
                  <i class="fa fa-check success"></i>
                @endif
              @endif

              @if ($question->type === 'date')              
                
                {!! Form::text($question->id.'-0', ($old_reply->first() !== NULL) ? $old_reply->first()->answer :  Request::old($question->id), ['data-type' => 'date', 'placeholder' => 'JJ/MM/AA']) !!}

              @elseif ($question->type === "member_email")

                {!! Form::text($question->id.'-0', ($old_reply->first() !== NULL) ? $old_reply->first()->answer :  Request::old($question->id), ['data-type' => 'email', 'placeholder' => 'son@email.com']) !!}

              @elseif ($question->type === "text")

                {!! Form::text($question->id.'-0', ($old_reply->first() !== NULL) ? $old_reply->first()->answer :  Request::old($question->id)) !!}

              @elseif ($question->type === "textarea")

                {!! Form::textarea($question->id.'-0', ($old_reply->first() !== NULL) ? $old_reply->first()->answer : Request::old($question->id)) !!}

              @elseif ($question->type == "children_details")


                <!-- This is to help the front for the old replies -->
                <!-- This number of fields must appear when the guy edit -->
                <?php $num_old_replies = count($old_reply->get()) ?>

                <!-- We will use it through all the inputs -->
                <?php $old_replies_names = $question->user_answers()->where('user_profile_id', '=', $profile->id)->where('to_referent_slug', '=', 'child_name')->get() ?>

                @if (count($old_replies_names) == 0)

                  <div data-name="empty-children" data-question="{{ $question->id }}"></div>

                @endif

                @for ($i = 0; $i <= 10; $i++)

                  <?php

                  if (isset($old_replies_names[$i])) $old_replies_name = $old_replies_names[$i];
                  else $old_replies_name = NULL;

                  ?>

                  <div data-name="children" data-question="{{ $question->id }}" class="hidden">

                    {!! Form::text($question->id.'-0['.$i.'][child_name]', 

                    (isset($old_replies_names[$i])) ? $old_replies_names[$i]->answer :  
                    Request::old($question->id), ['placeholder' => 'Prénom']) 

                    !!}

                    <div class="spacer20"></div>
                      <div class="row">
                        <?php

                        if ($old_replies_name !== NULL) $old_replies_sex = $question->user_answers()->where('to_referent_slug', '=', 'child_sex')->where('referent_id', '=', $old_replies_name->id)->first();
                        else $old_replies_sex = NULL;

                        ?>
                        
                        <div class="col-md-4">
                          {{ Form::select($question->id.'-0['.$i.'][child_sex]', generate_children_sex(), 

                          ($old_replies_sex !== NULL) ? $old_replies_sex->answer : 
                          Request::old($question->id), ['data-toggle' => 'fancyselect'])

                          }}
                        </div>

                        <?php

                        if ($old_replies_name !== NULL) $old_replies_year = $question->user_answers()->where('to_referent_slug', '=', 'child_year')->where('referent_id', '=', $old_replies_name->id)->first();
                        else $old_replies_year = NULL;

                        ?>
                        
                        <div class="col-md-3">
                          {!! Form::select($question->id.'-0['.$i.'][child_year]', generate_children_birth_form(), 

                          ($old_replies_year !== NULL) ? $old_replies_year->answer : 
                          Request::old($question->id), ['data-toggle' => 'fancyselect'])

                          !!}
                        </div>
                      
                        <?php

                        if ($old_replies_name !== NULL) $old_replies_month = $question->user_answers()->where('to_referent_slug', '=', 'child_month')->where('referent_id', '=', $old_replies_name->id)->first();
                        else $old_replies_month = NULL;

                        ?>
                        
                        <div class="col-md-3">
                          {!! Form::select($question->id.'-0['.$i.'][child_month]', generate_month_form(),

                          ($old_replies_month !== NULL) ? $old_replies_month->answer : 
                          Request::old($question->id), ['data-toggle' => 'fancyselect'])

                          !!}
                        </div>

                        <div class="col-md-2">

                          @if ($i >= 1)
                            <div class="spacer2"></div>
                            <a href="#" data-toggle="remove-children" class="btn btn-danger"><i class="fa fa-times"></i></a>
                          @endif

                        </div>
                      </div>
                      <div class="spacer30"></div>
          
                  </div>
                  
                @endfor

                <a id="add-children" href="#" class="spyro-btn spyro-btn-warning">Un de plus ?</a>

              @else

                <ul class="list-checkboxes">
                  @foreach ($question->answers()->get() as $answer)

                  <?php $answers = $profile->answers(); ?>
                  <?php $old_reply = $answers->where('box_question_id', $question->id); ?>

                    <li>

                      @if ($question->type === 'radiobutton')

                        @if ($old_reply->first() != NULL)
                          
              
                        {!! Form::radio($question->id.'-0', $answer->content, ($old_reply->first()->answer == $answer->content) ? true : Request::old($question->id.'-0'), array('id' => $answer->id)) !!}
                        @else
                        
                          {!! Form::radio($question->id.'-0', $answer->content, Request::old($question->id.'-0'), array('id' => $answer->id)) !!}
                        @endif

                        {!! Form::label($answer->id, $answer->content) !!}


                      @elseif ($question->type == 'checkbox')
                        

                        @if ($old_reply === NULL)

                          {!! Form::checkbox($question->id.'-'.$answer->id, $answer->content, Request::old($question->id.'-'.$answer->id), array('id' => $answer->id)) !!}

                        @else

    
                          {!! Form::checkbox($question->id.'-'.$answer->id, $answer->content, ($old_reply->where('answer', $answer->content)->first()) ? true : Request::old($question->id.'-'.$answer->id), array('id' => $answer->id)) !!}

                        @endif

                        {!! Form::label($answer->id, $answer->content) !!}

                        

                      @endif
                    </li>

                  @endforeach
                </ul>

              @endif

        </div>
        <div class="spacer40"></div>


      @endforeach

      <button type="submit"><i class="fa fa-check"></i> Valider</button>
      <nav>
        <ul class="pager">
          <li><a href="{{url('/order/choose-box')}}">&larr; Revenir au choix de la box</a></li>
        </ul>
      </nav>


      {!! Form::close() !!}
    </div>
  </div>

  <div class="spacer50"></div>
  @include('master-box.partials.footer')

@stop