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
    $blog_articles = BlogArticle::orderBy('created_at', 'desc')->get();
    
    return view('masterbox.guest.blog.index')->with(compact(
      'blog_articles'
    ));
  }

  public function getArticle($id)
  {

    $blog_article = BlogArticle::findOrFail($id);

    $random_articles = BlogArticle::orderByRaw("RAND()")->whereNotIn('id', [$blog_article->id])->limit(4)->get();

    return view('masterbox.guest.blog.article')->with(compact(
      'blog_article',
      'random_articles'
    ));


  }

  public function getRedirectContact()
  {
    return redirect()->action('MasterBox\Guest\ContactController@getIndex')->with('from_contact', true);
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