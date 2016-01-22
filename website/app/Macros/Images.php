<?php

/**
 * If we find an error for the label given, we output a text error
 */
Html::macro('resizeImage', function($type, $filename)
{

  $filenameCached = $type . '-' . $filename;

  if (is_file(public_path('cache/' . $filenameCached)))
  {
    return url('cache/' . $filenameCached);
  }

  return action('Shared\Service\ImagesController@getResize', ['type' => $type, 'filename' => $filename]);

});

?>