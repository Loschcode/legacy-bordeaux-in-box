@extends('masterbox.layouts.master')

@section('content')
  
  <div 
    id="gotham"
    data-controller="masterbox.customer.purchase.box-form"
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
            <p class="+text-center">Pour que la box soit la plus personnalisée possible, il te suffit de répondre à ces quelques questions à la place de ton amie !</p>
          @else
            <p class="+text-center">
              Pour que la box soit la plus personnalisée possible, il te suffit de répondre à ces quelques questions !
            </p>
          @endif
        </p>
      </div>
    </div>
    
    <div class="+spacer-small"></div>
    
    <div class="grid-7 grid-centered labelauty-default-small">
            
      @foreach ($questions as $key => $question)

          <div id="question-{{ $key+1 }}" class="+hidden" data-type="{{ $question->type }}">
            {{ Form::open() }}

            {{ Form::hidden('type', $question->type) }}
            {{ Form::hidden('question_id', $question->id) }}

            <div class="custombox">
              <div class="custombox__wrapper">
                <h3 class="custombox__question">Question n°{{ $key+1 }}</h3>
                <p class="custombox__description">{{ $question->question }}</p>
                @if ($question->type == 'checkbox')
                  <p class="custombox__multiple">(Plusieurs choix sont possibles)</p>
                @endif

                <div class="custombox__choices">
                  @if (in_array($question->type, ['date', 'member_email', 'text']))
                    {!! Form::text('answer', '', ['class' => 'form__input']) !!}
                  @elseif ($question->type == 'textarea')
                    {!! Form::textarea('answer', '', ['class' => 'form__input']) !!}
                  @else
                    @foreach ($question->answers as $answer)

                      @if ($question->type === 'radiobutton')

                        {!! Form::radio('answer', $answer->content, '', ['id' => $answer->id, 'data-labelauty' => $answer->content]) !!}

                      @else

                        {!! Form::checkbox('answer', $answer->content, '', ['id' => $answer->id, 'data-labelauty' => $answer->content]) !!}

                      @endif

                    @endforeach
                  @endif
                  <p id="error" class="custombox__error"></p>
                </div>
                <div class="custombox__footer">
                  @if ($question->type !== 'radiobutton')
                    <button type="submit" class="custombox__button" href="#"><i class="fa fa-check"></i> Enregistrer</button>
                    <a href="#" class="custombox__button --next js-skip"><i class="fa fa-arrow-circle-o-right"></i> Passer à la question suivante</a>
                  @else
                   <a href="#" class="custombox__button --next js-skip"><i class="fa fa-arrow-circle-o-right"></i> Passer à la question suivante</a>
                    <div id="loader" class="custombox__loader"></div>
                  @endif
                </div>
              </div>
            </div>
            {{ Form::close() }}
          </div>
      @endforeach
      
      <div class="+spacer-small"></div>
      <div class="+text-center">
        <a class="custombox__button --skip" href="#"><i class="fa fa-times-circle"></i> Je souhaite arrêter la personnalisation ici</a>
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
@stop
 <?php /*
      <div class="custombox labelauty-default">
        {!! Form::open() !!}
          @foreach ($questions as $question)
            
            {{-- Logical business --}}
            <?php $answers = $profile->answers(); ?>
            <?php $old_reply = $answers->where('box_question_id', $question->id); ?>
            
            {{ Form::label($question->id . '-0', $question->question, ['class' => 'custombox__question']) }}</h3>
            
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
      */ ?>