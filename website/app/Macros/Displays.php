<?php

use App\Models\Customer;

/**
 * Display a number formated as euro
 */
Html::macro('euros', function($number) {

  return euros($number);

});

/** 
 * Renders html structure to add a button in the navbar of admin section
 */
Html::macro('addButtonNavbar', function($title, $url) {

  return '<li class="navbar__item"><a class="navbar__link" href="' . $url . '">' . $title . '</a></li>';

});


/**
 * If we find an error for the label given, we output a text error
 */
Html::macro('checkError', function($label, $errors, $message_bag = '')
{

  // Case message bag
  if ( ! empty($message_bag)) {

    if ($errors->{$message_bag}->has($label)) {
      return '<p class="form__error">' . $errors->{$message_bag}->first($label) . '</p>'; 
    }

    return;

  }

  // Case without message bag
  if ($errors->has($label)) {
    return '<p class="form__error">' . $errors->first($label) . '</p>'; 
  }

  return;

});

/**
 * Check which link in the profile's menu is active. Returns the right css class
 */
Html::macro('cssLinkProfileMenuActive', function($label, $current) {

  if ($label === $current)
  {
    return '--active';
  }

  return false;

});

/**
 * Return the html to set in the spot checkbox
 */
Html::macro('getTextCheckboxSpot', function($delivery_spot, $order_building = '') 
{
  $output = 
    '<span class="labelauty-title">' . $delivery_spot->name . '</span>' .
    '<span class="labelauty-description"><i class="fa fa-map-marker labelauty-icon"></i>' . $delivery_spot->address . ', ' . $delivery_spot->city . ' (' . $delivery_spot->zip . ')</span>';

  if ( ! empty($order_building) && $delivery_spot->getDistanceFromCoordinate($order_building->destination_coordinate()->first()) > 0) {
    $output .= '<span class="labelauty-distance">Distance ' . display_distance($delivery_spot->getDistanceFromCoordinate($order_building->destination_coordinate()->first())) . '</span>';
  }

  return $output;
});

/**
 * Check if we need to return the class --complete for the pipeline
 * of masterbox.
 */
Html::macro('pipelineComplete', function($step_pipeline, $current_step) 
{

  if (Html::pipelineStepCompleted($step_pipeline, $current_step)) {
    return '--complete';
  }

});

/**
 * Check if the step of the pipeline is completed
 */
Html::macro('pipelineStepCompleted', function($step_pipeline, $current_step) {

  if ($step_pipeline <= $current_step) {
    return true;
  }

  return false;

});

/**
 * Check if the step of the pipeline is completed
 */
Html::macro('pipelinePaymentStepDone', function($current_step) {


  if ($current_step > 3) {
    return true;
  }

  return false;

});


/**
 * We will generate a link for the admin to access the user profile, from the email
 */
Html::macro('generateAdminLinkFromUserEmail', function($email)
{

  $customer = Customer::where('email', '=', $email)->first();
  if ($customer === NULL) return 'N/A';

  return "<a href='/admin/customers/focus/".$customer->id."'>".$customer->getFullName()."</a>";

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

  return '<div class="info info__wrapper"><i class="fa fa-info-circle"></i> ' . $info . '</div>';

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
Html::macro('displayQuizz', function ($profile, $spacer=" ", $long=false) {

  $questions = App\Models\BoxQuestion::get();
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
