<?php namespace App\Http\Controllers\Shared\Service;

use App\Http\Controllers\MasterBox\BaseController;
use Image, File;

class ImagesController extends BaseController {

  public function getResize($type, $filename)
  {

    // Get width and height depending the type of thumbnail wanted
    switch ($type) {
      case 'small':
        $width = 150;
        $height = 150;  
      break;
      
      case 'medium':
        $width = 300;
        $height = 300;
      break;

      case 'large':
        $width = 600;
        $height = 600;
      break;

      default:
        $width = 300;
        $height = 300;
      break;
    }

    // Find the picture
    $files = File::glob(public_path('uploads/*/' . $filename));

    // We didn't find the file
    if (empty($files)) {
      return;
    }

    // Fetch the first picture
    $file = $files[0];

    // Return the picture resized
    return Image::make($file)
      ->resize($width, $height, function($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
      })
      ->response();

  }

}
