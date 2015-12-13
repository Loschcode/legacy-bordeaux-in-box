<?php namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;

use App\Models\ImageArticle;

class IllustrationsController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Default Illustration Controller
  |--------------------------------------------------------------------------
  |
  | Illustration page system
  |
  */

  /**
   * Illustrations
   */
  public function getIndex($id=NULL)
  {
    if ($id === NULL)
    {
      $next_article = NULL;
      $image_article = ImageArticle::orderBy('created_at', 'desc')->first();

      if ($image_article !== NULL) 
      {
        $previous_article = $image_article->get_previous();
      } 
      else 
      {
        $previous_article = NULL;
      }      

    } 
    else 
    {
      $image_article = ImageArticle::find($id);

      if ($image_article === NULL)
      {
        abort(404);
      }

      $previous_article = $image_article->get_previous();
      $next_article = $image_article->get_next();

    }

    return view('illustrations.index')->with(compact(
      'next_article',
      'image_article',
      'previous_article'
    ));

  }

  public function checkSeoIllustrations($id, $slug)
  {
    $image_article = ImageArticle::find($id);

    // If NULL
    if ($image_article === NULL)
    {
      return abot(404);
    }

    // If not correct slug
    if ($slug !== $image_article->slug)
    {
      return redirect('illustration/'.$id.'-'.$image_article->slug); // SEO Optimized
    }

    return $this->getIndex($id);
  }

}
