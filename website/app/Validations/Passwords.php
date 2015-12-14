<?php

/**
 * Check if this field match the current user password
 */
Validator::extend('match_password', function($attribute, $value, $parameters)
{
  if (Hash::check($value, Auth::user()->password))
  {
    return true;
  }
  else
  {
    return false;
  }
});