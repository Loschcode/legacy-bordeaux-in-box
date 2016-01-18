<?php

function order_spot_or_destination_zip($order) {

  if ($order->take_away == TRUE) {

    $spot = $order->delivery_spot()->first();

    if ($spot != NULL) {

      $output = $spot->zip;

    } else {

      $output = '';

    }

  } else {

    $destination = $order->destination()->first();

    if ($destination != NULL) {

      $output = $destination->zip;

    } else {

      $output = '';

    }
  }

  return $output;

}

function order_spot_or_destination($order) {

  if ($order->take_away == TRUE) {

    $spot = $order->delivery_spot()->first();

    if ($spot != NULL) {

      $output = '<strong>'.$spot->name.'</strong><br />'.$spot->city.', '.$spot->zip.'<br />'.$spot->address.'<br />';

    } else {

      $output = 'Inconnue';

    }

  } else {

    $destination = $order->destination()->first();

    if ($destination != NULL) {

      $output = '<strong>'.$destination->last_name.' '.$destination->first_name.'</strong><br />'.$destination->city.', '.$destination->zip.'<br />'.$destination->address.'<br />';

    } else {

      $output = 'Inconnue';

    }
  }

  return $output;

}

function order_questions_and_answers($box, $profile, $spacer=", ") {

  $questions = $box->questions()->get();
  $output = '';

  foreach ($questions as $question) {

    $output .= $question->question.' - ';

    $answers = $profile->answers();
    $old_reply = $answers->where('box_question_id', $question->id);

    if ($question->type === "text") {

      if ($old_reply->first() != NULL)
      $output .= $old_reply->first()->answer;

    } elseif ($question->type === "textarea") {

      if ($old_reply->first() != NULL)
      $output .= $old_reply->first()->answer;

    } else {

      if ($question->answers()->first() == NULL) {

        $output .= 'Aucune';

      }

      foreach ($old_reply->get() as $answer) {

        $output .= $answer->answer.$spacer; 

      }

    }

    $output .= '<br />';

  }

  return $output;

}

function order_questions($profile, $spacer=" - ") {

  $questions = BoxQuestion::get();
  $output = '';

  foreach ($questions as $question) {

    
    if (empty($question->short_question)) $final_question = $question->question;
    else $final_question = $question->short_question;

    $output .= $final_question.$spacer;

    $output .= '<br />';

  }

  return $output;

}

function order_answers($profile, $spacer=", ") {

  $questions = BoxQuestion::get();
  $output = '';

  foreach ($questions as $question) {

    $answers = $profile->answers();
    $old_reply = $answers->where('box_question_id', $question->id);

    if ($question->type === "text") {

      if ($old_reply->first() != NULL)
      $output .= $old_reply->first()->answer;

    } elseif ($question->type === "textarea") {

      if ($old_reply->first() != NULL)
      $output .= $old_reply->first()->answer;

    } else {

      if ($question->answers()->first() == NULL) {

        $output .= 'Aucune';

      }

      foreach ($old_reply->get() as $answer) {

        $output .= $answer->answer.$spacer; 

      }

    }

    $output .= '<br />';

  }

  return $output;

}