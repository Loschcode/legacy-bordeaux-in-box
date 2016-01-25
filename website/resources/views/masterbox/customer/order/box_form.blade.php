@extends('masterbox.layouts.master')

@section('content')
  
  <div 
    id="gotham"
    data-form-errors="{{ $errors->has() }}"
    data-form-errors-text="Des erreurs sont présentes dans le formulaire"
  ></div>

  <div class="container">
    
    {{-- Pipeline --}}
    @include('masterbox.partials.pipeline', ['step' => 4])

    {{-- Section --}}
    <div class="grid-9 grid-centered">
      <div class="section">
        <h2 class="section__title --choose-frequency">
          @if ($order_preference->isGift())
            Personnalise sa box
          @else
            Personnalise ta box
          @endif
        </h2>
        <p class="section__description --choose-frequency">
          @if ($order_preference->isGift())
            <p>Pour que la box soit la plus personnalisée possible, réponds à ces quelques questions à la place de ton amie !</p>
          @else
            <p>
              Pour que la box soit la plus personnalisée possible, répondez à ces quelques questions !
            </p>
          @endif
        </p>
      </div>
    </div>
    
    <div class="+spacer-small"></div>
    
    <div class="grid-8 grid-centered">
      
      <div class="+text-center">
        <a href="{{ action('MasterBox\Customer\PurchaseController@getConfirmed') }}" class="button button__skip-custom-box">Je ne veux pas personnaliser la box</a>
      </div>

      <div class="custombox labelauty-default">
        {!! Form::open() !!}
          @foreach ($questions as $question)
            
            {{-- Logical business --}}
            <?php $answers = $profile->answers(); ?>
            <?php $old_reply = $answers->where('box_question_id', $question->id); ?>
            
            <h3 class="custombox__question">{{$question->question}}</h3>
            
            @if ($question->type === 'date')              
              
              {!! Form::text($question->id.'-0', ($old_reply->first() !== NULL) ? $old_reply->first()->answer :  Request::old($question->id), ['class' => 'custombox__input']) !!}

            @elseif ($question->type === "member_email")

              {!! Form::text($question->id.'-0', ($old_reply->first() !== NULL) ? $old_reply->first()->answer :  Request::old($question->id), ['class' => 'custombox__input']) !!}

            @elseif ($question->type === "text")

              {!! Form::text($question->id.'-0', ($old_reply->first() !== NULL) ? $old_reply->first()->answer :  Request::old($question->id), ['class' => 'custombox__input']) !!}

            @elseif ($question->type === "textarea")

              {!! Form::textarea($question->id.'-0', ($old_reply->first() !== NULL) ? $old_reply->first()->answer : Request::old($question->id), ['class' => 'custombox__input']) !!}
            
            @else
              
              <div class="labelauty-no-label">
                @foreach ($question->answers()->get() as $answer)

                <?php $answers = $profile->answers(); ?>
                <?php $old_reply = $answers->where('box_question_id', $question->id); ?>


                    @if ($question->type === 'radiobutton')

                      @if ($old_reply->first() != NULL)
                        
              
                      {!! Form::radio($question->id.'-0', $answer->content, ($old_reply->first()->answer == $answer->content) ? true : Request::old($question->id.'-0'), array('id' => $answer->id, 'data-labelauty' => '')) !!}
                        <div class="custombox__label">{{ $answer->content }}</div>
                        <div class="+spacer-extra-small"></div>
                      @else
                      
                        {!! Form::radio($question->id.'-0', $answer->content, Request::old($question->id.'-0'), array('id' => $answer->id, 'data-labelauty' => '')) !!}
                        <div class="custombox__label">{{ $answer->content }}</div>
                        <div class="+spacer-extra-small"></div>

                      @endif


                    @elseif ($question->type == 'checkbox')
                      

                      @if ($old_reply === NULL)

                        {!! Form::checkbox($question->id.'-'.$answer->id, $answer->content, Request::old($question->id.'-'.$answer->id), array('id' => $answer->id, 'data-labelauty' => '')) !!}
                        <div class="custombox__label">{{ $answer->content }}</div>
                        <div class="+spacer-extra-small"></div>


                      @else

              
                        {!! Form::checkbox($question->id.'-'.$answer->id, $answer->content, ($old_reply->where('answer', $answer->content)->first()) ? true : Request::old($question->id.'-'.$answer->id), array('id' => $answer->id, 'data-labelauty' => '')) !!}
                        <div class="custombox__label">{{ $answer->content }}</div>
                        <div class="+spacer-extra-small"></div>


                      @endif

                      

                    @endif

                @endforeach
              </div>

            @endif


          @endforeach
          
          <div class="+spacer-small"></div>

          <button class="button button__submit --big"><i class="fa fa-check"></i> Valider</button>
        {!! Form::close() !!}
      </div>
    </div>

    <!-- Facebook Conversion Code for Paiements -->
    <script>(function() {
      var _fbq = window._fbq || (window._fbq = []);
      if (!_fbq.loaded) {
        var fbds = document.createElement('script');
        fbds.async = true;
        fbds.src = '//connect.facebook.net/en_US/fbds.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(fbds, s);
        _fbq.loaded = true;
      }
    })();
    window._fbq = window._fbq || [];
    window._fbq.push(['track', '6022362413870', {'value':'{{ number_format($order_preference->unity_price, 2) }}','currency':'EUR'}]);
    </script>
    <noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6022362413870&amp;cd[value]={{ number_format($order_preference->unity_price, 2) }}&amp;cd[currency]=EUR&amp;noscript=1" /></noscript>

  </div>

  <?php /*
  <div id="js-page-box-form"></div>

  @include('masterbox.partials.pipeline', ['step' => 4])

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
        	<a href="{{ action('MasterBox\Customer\PurchaseController@getConfirmed') }}" class="spyro-btn spyro-btn-warning spyro-btn-lg icon-space">Je ne veux pas personnaliser la box <i class="fa fa-angle-right"></i></a>
        </p>
      </div>
    </div>
  </div>

  <div class="clearfix"></div>
  <div class="spacer50"></div>

  <div class="container">

    <div class="col-md-8 col-md-offset-2">

      {!! Form::open(['class' => 'form-component']) !!}

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
                <?php $old_replies_names = $question->customer_answers()->where('customer_profile_id', '=', $profile->id)->where('to_referent_slug', '=', 'child_name')->get() ?>

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

                        if ($old_replies_name !== NULL) $old_replies_sex = $question->customer_answers()->where('to_referent_slug', '=', 'child_sex')->where('referent_id', '=', $old_replies_name->id)->first();
                        else $old_replies_sex = NULL;

                        ?>
                        
                        <div class="col-md-4">
                          {{ Form::select($question->id.'-0['.$i.'][child_sex]', generate_children_sex(), 

                          ($old_replies_sex !== NULL) ? $old_replies_sex->answer : 
                          Request::old($question->id), ['data-toggle' => 'fancyselect'])

                          }}
                        </div>

                        <?php

                        if ($old_replies_name !== NULL) $old_replies_year = $question->customer_answers()->where('to_referent_slug', '=', 'child_year')->where('referent_id', '=', $old_replies_name->id)->first();
                        else $old_replies_year = NULL;

                        ?>
                        
                        <div class="col-md-3">
                          {!! Form::select($question->id.'-0['.$i.'][child_year]', generate_children_birth_form(), 

                          ($old_replies_year !== NULL) ? $old_replies_year->answer : 
                          Request::old($question->id), ['data-toggle' => 'fancyselect'])

                          !!}
                        </div>
                      
                        <?php

                        if ($old_replies_name !== NULL) $old_replies_month = $question->customer_answers()->where('to_referent_slug', '=', 'child_month')->where('referent_id', '=', $old_replies_name->id)->first();
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


      {!! Form::close() !!}
    </div>
  </div>

  <div class="spacer50"></div>
  */ ?>
@stop