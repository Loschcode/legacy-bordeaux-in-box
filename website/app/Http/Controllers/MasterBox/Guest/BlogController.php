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

  public function getArticle($slug)
  {
    $blog_article = BlogArticle::where('slug', '=', $slug)->first();

    if ($blog_article === NULL)
      return redirect()->action('MasterBox\Guest\BlogController@getIndex');

    $random_articles = BlogArticle::orderByRaw("RAND()")->whereNotIn('id', [$blog_article->id])->limit(4)->get();

    return view('masterbox.guest.blog.article')->with(compact(
      'blog_article',
      'random_articles'
    ));


  }

}