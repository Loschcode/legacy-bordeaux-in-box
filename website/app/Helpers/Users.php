<?php

use App\Models\BoxQuestion;
use App\Models\BoxQuestionCustomerAnswer;

function get_age($dateBirthday) {

  // It's an european date
  $dateBirthday = str_replace('/', '-', $dateBirthday);

  $birthday = \Carbon\Carbon::parse($dateBirthday);
  $now = \Carbon\Carbon::now('Europe/Paris');

  return $now->diffInYears($birthday);

}

function is_birthday($dateBirthday) {

  // It's an european date
  $dateBirthday = str_replace('/', '-', $dateBirthday);

  $birthday = \Carbon\Carbon::parse($dateBirthday);
  $now = \Carbon\Carbon::now('Europe/Paris');

  if ($birthday->month == $now->month)
  {
    return true;
  }

  return false;

}

function get_category_from_children_special_fields($age) {

  // From the oldest to the youngest
  $categories = array_reverse(Config::get('bdxnbx.children_special_fields'));

  foreach ($categories as $slug_category => $category) {

    if ($age >= $category['min_age']) return $slug_category;

  }

  return FALSE;

}

function get_category_from_date_age($age) {

  // From the oldest to the youngest
  $categories = array_reverse(Config::get('bdxnbx.date_age_special_fields'));

  foreach ($categories as $slug_category => $category) {

    if ($age >= $category['min_age']) return $slug_category;

  }

  return FALSE;

}

function convert_date_to_age($string) {

    $string = str_replace('/', '-', $string); // To be understood

    if (strlen($string) === 4) $string = $string.'-01-01'; // If it only a year, we add artificially the month and days

    $birthday = new DateTime($string);
    $interval = $birthday->diff(new DateTime);

    return $interval->y;
    
}

function convert_model_object_to_ids_array($object) {

  $final_array = [];
  foreach ($object as $datas) {

    array_push($final_array, (int) $datas['id']);

  }

  return $final_array;

}

/**
 * Will refresh the whole answers of the user depending on a specific question form
 * Everything is managed dynamically.
 */
function refresh_answers_from_dynamic_questions_form($fields, $profile) {

  // In case of edition we remove the old answers
  foreach ($profile->answers()->get() as $answer) {

    $answer->delete();

  }

  // We will generate the answers for the user
  foreach ($fields as $raw_id => $answer) {

    $arr_id = explode('-', $raw_id);
    $matching_question = BoxQuestion::find($arr_id[0]);

    // It means it's a valid answer (not _token or some other fields)
    if (isset($arr_id[1])) {

      /**
       * Special case children_details
       * This will be an array containing all the different replies for each children
       * It will be treated completely differently by the system
       *
       * To work, the 'name' input must be in first place
       * It will be considered as the referent for the other entries
       * This is very manual and spaghetti but there's no other way right now.
       * 
       */
      if ($matching_question->type == 'children_details') {

        foreach ($answer as $kid) {

          foreach ($kid as $to_referent_slug => $real_answer) {

            // If anything else is filled, we can validate the name as unknown
            // -> It will match for the name in this case
            if (empty($real_answer) && ($to_referent_slug == 'child_name')) {

              $comparison = implode($kid);
              if ($comparison !== "000") $real_answer = "?";

            }

            // We make the name pretty
            if ($to_referent_slug == 'child_name') {

              $real_answer = ucfirst(strtolower($real_answer));

            }

            // Will work only if the answers are valid
            if (($real_answer != "0") && (!empty($real_answer))) {

              $customer_answer = new BoxQuestionCustomerAnswer;
              $customer_answer->profile()->associate($profile);

              $box_question = $matching_question;
              $customer_answer->box_question()->associate($box_question);

              // Every other answers from this will be linked with the parent 'name'
              // It's the master entry
              if (($to_referent_slug != 'child_name') && (isset($referent_id))) {

                $customer_answer->referent_id = $referent_id;

              }

              $customer_answer->to_referent_slug = $to_referent_slug;
              $customer_answer->answer = $real_answer;
              $customer_answer->save();

              if ($to_referent_slug == 'child_name') {

                $referent_id = $customer_answer->id;

              }

            }

          }

          // For each kid we will get a new referent, we don't want to risk to have the same
          unset($referent_id);

        }

      } else {

        /**
         * Classical system, nothing special compared to above
         */
        if (!empty($answer)) {
          
          $customer_answer = new BoxQuestionCustomerAnswer;
          $customer_answer->profile()->associate($profile);

          /**
           * Date is kind of special too
           * It has a to_referent_slug even tho there's no other field
           * It's for the filter to work without SQL queries
           */
          if ($matching_question->type == 'date') {

            $customer_answer->to_referent_slug = 'date_age';

          }

          $box_question = $matching_question;
          $customer_answer->box_question()->associate($box_question);

          $customer_answer->answer = $answer;

          $customer_answer->save();

        }

      }

    }

  }

}

function generate_children_birth_form($years=25) {

  $dropdown = ['0' => '-'];

  $year = date("Y") - $years;
  $dropdown[$year] = "Avant $year";

  $num = $years-1;
  while ($num > 0) {

    $year = date("Y") - $num;
    $dropdown[$year] = "De $year";
    $num--;

  }

  $year = date("Y");
  $dropdown[$year] = "De/Pour $year";

  $year = date("Y") + 1;
  $dropdown[$year] = "Pour $year";

  return $dropdown;

}
