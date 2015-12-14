<?php

/**
 * We take the contact possible services
 */
Form::macro('hasNoAnswerPossible', function($type)
{

  $arr_check = Config::get('bdxnbx.no_answer_question_type');

  if (in_array($type, $arr_check)) return TRUE;
  else return FALSE;

});