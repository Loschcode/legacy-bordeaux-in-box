<?php namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;

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
     * The layout that should be used for responses.
     */
    public $layout = 'layouts.master';

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

			} else {

				$previous_article = NULL;

			}			

		} else {

			$image_article = ImageArticle::find($id);

			if ($image_article === NULL) return Response::error(404);

			$previous_article = $image_article->get_previous();
			$next_article = $image_article->get_next();

		}

		view()->share('next_article', $next_article);
		view()->share('image_article', $image_article);
		view()->share('previous_article', $previous_article);

		$this->layout->content = view()->make('illustrations.index');

	}

	public function checkSeoIllustrations($id, $slug)
	{

		$image_article = ImageArticle::find($id);

		// If NULL
		if ($image_article === NULL)
		{
			return Response::error(404);
		}

		// If not correct slug
		if ($slug !== $image_article->slug)
		{
		return Redirect::to('illustration/'.$id.'-'.$image_article->slug); // SEO Optimized
		}

		return $this->getIndex($id);

	}

}
