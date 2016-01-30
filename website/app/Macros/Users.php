<?php

/**
 * Macro to display simple info designed
 */
Html::macro('getAge', function($dateBirthday) {

  return get_age($dateBirthday);

});

Html::macro('isBirthday', function($dateBirthday) {

  return is_birthday($dateBirthday);

});