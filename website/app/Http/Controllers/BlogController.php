<?php namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;

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
     * The layout that should be used for responses.
     */
    protected $layout = 'layouts.master';

    /**
     * Illustrations
     */
	public function getIndex()
	{

		$blog_articles = BlogArticle::orderBy('created_at', 'desc')->paginate(5);
		View::share('blog_articles', $blog_articles);
		
		$this->layout->content = View::make('blog.index');

	}

	public function getArticle($id)
	{

		$blog_article = BlogArticle::find($id);

		if ($blog_article === NULL) return Response::error(404);

		$previous_article = $blog_article->get_previous();
		$next_article = $blog_article->get_next();

		View::share('blog_article', $blog_article);
		View::share('previous_article', $previous_article);
		View::share('next_article', $next_article);

		$this->layout->content = View::make('blog.article');


	}

	public function checkSeoBlog($id, $slug)
	{

		$blog_article = BlogArticle::find($id);

		// If NULL
		if ($blog_article === NULL)
		{
			return Redirect::to('/');
		}

		// If not correct slug
		if ($slug !== $blog_article->slug)
		{
		return Redirect::to('blog/'.$id.'-'.$blog_article->slug); // SEO Optimized
		}

		return $this->getArticle($id);

	}

}