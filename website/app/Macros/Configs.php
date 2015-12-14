<?php

/**
 * We take the contact possible services
 */
Form::macro('getContactServices', function()
{

  return Config::get('bdxnbx.contact_service');

});

/**
 * We take the possible questions type while creating questions box
 */
Form::macro('getPossibleQuestionTypes', function()
{

  return Config::get('bdxnbx.question_types');

});