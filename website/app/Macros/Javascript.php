<?php

/**
 * Abstract gotham HTML
 */
Html::macro('gotham', function($overrides = [])
{

  $default = array_merge([
    'form-errors' => session()->has('errors'),
    'success-message' => session()->get('message'),
    'error-message' => session()->get('error')
  ], $overrides);

  $output = '<div id="gotham"';

  foreach ($default as $key => $data)
  {
    $output .= ' data-' . $key . '="' . $data . '"';
  }

  $output .= ' />';

  return $output;

});
