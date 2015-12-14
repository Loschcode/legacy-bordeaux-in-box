<?php

/**
 * We take the contact possible services
 */
Html::macro('getContactServices', function()
{

  return Config::get('bdxnbx.contact_service');

});

/**
 * We take the possible questions type while creating questions box
 */
Html::macro('getPossibleQuestionTypes', function()
{

  return Config::get('bdxnbx.question_types');

});