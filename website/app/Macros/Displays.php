<?php

use App\Models\Customer;

/**
 * We will generate a link for the admin to access the user profile, from the email
 */
Html::macro('generateAdminLinkFromUserEmail', function($email)
{

  $customer = Customer::where('email', '=', $email)->first();
  if ($customer === NULL) return 'N/A';

  return "<a href='/admin/users/focus/".$customer->id."'>".$customer->getFullName()."</a>";

});

/**
 * Get html class color from the box slug
 */
Html::macro('getColorFromBoxSlug', function($slug)
{

  $arr_check = Config::get('bdxnbx.box_spyro_color');

  if (isset($arr_check[$slug])) return $arr_check[$slug];
  else return '';

});

/**
 * Macro to display simple info designed
 */
Html::macro('info', function($info) {

  return '<div class="spyro-alert spyro-alert-inverse"><p class="left"><i class="fa fa-info"></i></p><p class="right">' . $info . '</p><div class="clearfix"></div></div>';

});

/**
 * We get the order spot / destination
 */
Html::macro('getOrderSpotOrDestination', function($order)
{

  return order_spot_or_destination($order);

});

/**
 * We get the order spot / destination
 */
Html::macro('getOrderSpotOrDestinationZip', function($order)
{

  return order_spot_or_destination_zip($order);

});

/**
 * We output the questions and answers in HTML (for the admin dashboard orders reading)
 */
Html::macro('getOrderQuestionsAndAnswers', function($box, $profile)
{

  return order_questions_and_answers($box, $profile);

});

/**
 * Macro to display in the dashboard answers of the "quizz"
 */
Html::macro('displayQuizz', function ($box, $profile, $spacer=" ", $long=false) {

  $questions = $box->questions()->get();
  $output = '<div class="well">';

  foreach ($questions as $question) {

    if ((($long) && (empty($question->short_question))) or (empty($question->short_question))) $final_question = $question->question;
    else $final_question = $question->short_question;

    $output .= '<strong>' . $final_question . '</strong><br/>';

    $answers = $profile->answers();
    $old_reply = $answers->where('box_question_id', $question->id);

    if ($question->type === "text") {

      if ($old_reply->first() != NULL) $output .= $old_reply->first()->answer; else $output .= 'N/A';

    } elseif ($question->type === "textarea") {

      if ($old_reply->first() != NULL) $output .= $old_reply->first()->answer; else $output .= 'N/A';

    } elseif ($question->type === "date") {

      if ($old_reply->first() != NULL) $output .= $old_reply->first()->answer; else $output .= 'N/A';

    } else {

      if ($question->answers()->first() == NULL) {

        $output .= 'N/A';

      }

      foreach ($old_reply->get() as $answer) {

        $output .= $answer->answer.$spacer;

      }

    }

    $output .= '<br /><br/>';

  }

  $output .= '</div>';
  return $output;

});
