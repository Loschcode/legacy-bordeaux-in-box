<?php namespace App\Http\Controllers\MasterBox\Guest;

use App\Http\Controllers\MasterBox\BaseController;

use App\Models\BlogArticle;

class BlogController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Default Blog Controller
  |--------------------------------------------------------------------------
  |
  | Illustration page system
  |
  */

  /**
   * Illustrations
   */
  public function getIndex()
  {
    $blog_articles = BlogArticle::orderBy('created_at', 'desc')->paginate(5);
    
    return view('blog.index')->with(compact(
      'blog_articles'
    ));
  }

  public function getArticle($id)
  {

    $blog_article = BlogArticle::findOrFail($id);

    $previous_article = $blog_article->get_previous();
    $next_article = $blog_article->get_next();

    return view('blog.article')->with(compact(
      'blog_article',
      'previous_article',
      'next_article'
    ));


  }

  public function checkSeoBlog($id, $slug)
  {

    $blog_article = BlogArticle::findOrFail($id);

    // If not correct slug
    if ($slug !== $blog_article->slug) {

      return redirect('blog/'.$id.'-'.$blog_article->slug); // SEO Optimized
      
    }

    return $this->getArticle($id);

  }

}