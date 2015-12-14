<?php

/**
 * Macro to display simple info designed
 */
Form::macro('getAge', function($dateBirthday) {

  // It's an european date
  $dateBirthday = str_replace('/', '-', $dateBirthday);

  $birthday = \Carbon\Carbon::parse($dateBirthday);
  $now = \Carbon\Carbon::now('Europe/Paris');

  return $now->diffInYears($birthday);

});

Form::macro('isBirthday', function($dateBirthday) {

  return is_birthday($dateBirthday);

});