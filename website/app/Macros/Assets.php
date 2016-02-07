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

/**
 * Suffix the asset (css/js) given with a timestamp 
 * to avoid bad caching in the browser.
 *
 * @param  $file The file 
 * @return  string
 *
 * @example
 *
 * Html::version('stylesheets/app.css')
 */
Html::macro('version', function($file) {

  $path = public_path($file);

  // Do nothing if the file do not exist
  if ( ! file_exists($path)) {
    return url($path);
  }

  return url($file . '?version=' . filemtime($path));

});