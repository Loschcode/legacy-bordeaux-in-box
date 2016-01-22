<?php

/**
 * If we find an error for the label given, we output a text error
 */
Html::macro('resizeImage', function($type, $filename)
{

  return action('Shared\Service\ImagesController@getResize', ['type' => $type, 'filename' => $filename]);

});

?>