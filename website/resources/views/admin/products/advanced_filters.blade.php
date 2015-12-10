@section('page')
  <i class="fa fa-filter"></i> Filtres avancés {{$product->name}}  (#{{$product->id}})
@stop

@section('buttons')

@if (URL::previous() != Request::root())
  <a href="{{URL::previous()}}#filters" class="spyro-btn spyro-btn-success">Retour</a>
@endif

@stop

@section('content')

  @if (Session::has('message'))
    <div class="js-alert-remove spyro-alert spyro-alert-success">{{ Session::get('message') }}</div>
  @endif

  @if ($errors->has())
    <div class="js-alert-remove spyro-alert spyro-alert-danger">Erreurs dans le formulaire</div>
  @endif

  {!! HTML::info('Les filtres avancés permettent de filtrer précisément certains produits via le formulaire personnalisé que les utilisateurs remplissent. Pour accepter uniquement certaines réponses, cochez les cases, pour exiger certains réponses précises, remplissez les champs. <br /><br />NOTE : réfléchissez en terme de conditions, pour un produit qui correspond à quelqu\'un qui a la peau sèche ET qui aime le bleu, il suffit de cocher les deux cases. Si le produit correspond à quelqu\'un qui aime le bleu OU le vert, il suffit de cocher les deux cases de la même section.') !!}

  {!! Form::open(['action' => 'AdminProductsController@postAdvancedProductFilters']) !!}

  {!! Form::hidden("product_id", $product->id) !!}

  @foreach ($filter_boxes as $filter_box)

    <h3>{{$filter_box->box()->first()->title}}</h3>
    <hr />

    <br />

    <!-- BEGINNING FILTERS FOR THIS BOX -->

    @foreach ($filter_box->box()->first()->questions()->get() as $question)

    <div class="form-group">

     <h4>{{$question->short_question}}

     @if ($question->filter_must_match)

      <font color='red' size='3em'><em>Ce filtre doit obligatoirement être respecté</em></font>

     @endif

     </h4>

     @if (($question->type === "text") || ($question->type === 'member_email'))

      @if (isset($autofill_texts[$question->id]))
        {!! Form::text($question->id, $autofill_texts[$question->id]) !!}
      @else
        {!! Form::text($question->id, "") !!}
      @endif

     @elseif ($question->type === "textarea")

     {!! Form::textarea($question->id, "") !!}

     @elseif ($question->type == "date")

       @foreach (Config::get('bdxnbx.date_age_special_fields') as $person_type => $person_details)

          @if (isset($autofill_checkboxes[$question->id]['date_age'][$person_type]))
            {!! Form::checkbox($question->id.'[date_age][]', $person_type, true, array('id' => $question->id.'-'.$person_type)) !!}
          @else
            {!! Form::checkbox($question->id.'[date_age][]', $person_type, false, array('id' => $question->id.'-'.$person_type)) !!}
          @endif
          
          {!! Form::label($question->id.'-'.$person_type, $person_details['label'] . ' (>'.$person_details['min_age'].' ans)') !!}

        @endforeach

     @elseif ($question->type == "children_details")

     Prénom {!! Form::text($question->id.'[child_name]', (isset($autofill_texts[$question->id]['child_name'])) ? $autofill_texts[$question->id]['child_name'] : "") !!}<br />
     Sexe

    @foreach (Config::get('bdxnbx.children_sex_fields') as $child_sex)

      {!! Form::checkbox($question->id.'[child_sex][]', $child_sex, 
      (isset($autofill_checkboxes[$question->id]['child_sex'][$child_sex])) ? TRUE : FALSE, array('id' => $question->id.'-'.$child_sex)) !!}
      
      {!! Form::label($question->id.'-'.$child_sex, $child_sex) !!}

     @endforeach

     <br />
     Tranche d'âge

     @foreach (Config::get('bdxnbx.children_special_fields') as $child_type => $child_details)

        @if (isset($autofill_checkboxes[$question->id]['child_age'][$child_type]))
          {!! Form::checkbox($question->id.'[child_age][]', $child_type, true, array('id' => $answer->id.'-'.$child_type)) !!}
        @else
          {!! Form::checkbox($question->id.'[child_age][]', $child_type, false, array('id' => $answer->id.'-'.$child_type)) !!}
        @endif
        {!! Form::label($answer->id.'-'.$child_type, $child_details['label'] . ' (>'.$child_details['min_age'].' ans)') !!}

      @endforeach

     @else

      @if ($question->answers()->first() == NULL)

      Aucune

      @endif

      @foreach ($question->answers()->get() as $answer)

      @if (($question->type === 'radiobutton') || ($question->type === 'checkbox'))

        @if (isset($autofill_checkboxes[$question->id][$answer->content]))
          {!! Form::checkbox($question->id."[]", $answer->content, true, array('id' => $answer->id)) !!}
        @else
          {!! Form::checkbox($question->id."[]", $answer->content, false, array('id' => $answer->id)) !!}
        @endif

        {{ Form::label($answer->id, $answer->content)}}

      @endif

      @endforeach

    @endif

    </div>

    <div class="spacer20"></div>

    @endforeach

    <!-- END FILTERS FOR THIS BOX -->

  @endforeach

  {!! Form::submit("Mettre à jour ces filtres", ['class' => 'spyro-btn spyro-btn-success']) !!}

  {!! Form::close() !!}
@stop