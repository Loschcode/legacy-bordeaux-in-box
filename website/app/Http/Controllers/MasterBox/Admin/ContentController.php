<?php namespace App\Http\Controllers\MasterBox\Admin;

use App\Http\Controllers\MasterBox\BaseController;

use App\Models\BlogArticle;
use App\Models\ImageArticle;
use App\Models\Page;

use Request, Validator, Auth, Str;

class ContentController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Admin Illustration Controller
	|--------------------------------------------------------------------------
	|
	| Add / Edit / Delete blog
	|
	*/

    /**
     * Filters
     */
    public function __construct()
    {
    	
    	$this->beforeMethod();

    }
    

    /**
     * Get the listing page of the blog
     * @return void
     */
	public function getIndex()
	{

		$blog_articles = BlogArticle::orderBy('created_at', 'desc')->get();
		$image_articles = ImageArticle::orderBy('created_at', 'desc')->get();

		$pages = Page::get();

		return view('master-box.admin.content.index')->with(compact(
      'pages',
      'image_articles',
      'blog_articles'
    ));

	}

	/**
	 * We remove the illustration
	 */
	public function getDeleteBlog($id)
	{
		$blog_article = BlogArticle::findOrFail($id);

		$blog_article->delete();

		session()->flash('message', "L'article de blog a été correctement supprimé");
		return redirect()->back();
	}

	/**
	 * We a edit an illustration
	 */
	public function getEditBlog($id)
	{
		$blog_article = BlogArticle::findOrFail($id);

		return view('master-box.admin.content.blog.edit')->with(compact(
      'blog_article'
    ));
	}

	public function postEditBlog()
	{

		// New article rules
		$rules = [

			'blog_article_id' => 'required|integer',
			'title' => 'required|min:5',
			'url' => '',
			'slug' => 'required',
			'content' => 'required|min:5',

			'thumbnail' => 'image'

			];


		$fields = Request::all();

		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

			$blog_article = BlogArticle::findOrFail($fields['blog_article_id']);


			$blog_article->title = $fields['title'];
			$blog_article->slug = $fields['slug'];
			$blog_article->url = $fields['url'];
			$blog_article->content = $fields['content'];
			$blog_article->user()->associate(Auth::user());
			
			if ( ! empty($fields['thumbnail']))
			{

				// We manage the thumbnail
				$file = Request::file('thumbnail');
				$destinationPath = 'public/uploads/blog/';

				$filename = value(function() use ($file, $blog_article) {

					$filename = Str::slug($blog_article->title) . '.' . $file->getClientOriginalExtension();
					return $filename;

				});

				Request::file('thumbnail')->move($destinationPath, $filename);

				$thumbnail = ['folder' => 'blog', 'filename' => $filename];
				$blog_article->thumbnail = json_encode($thumbnail);

			}

			$blog_article->save();

			return redirect()->to('/admin/content#blog')
			->with('message', 'Modifications effectuées')
			->withInput();

		} else {

			// We return the same page with the error and saving the input datas
			return redirect()->back()
			->withInput()
			->withErrors($validator);

		}



	}

    /**
     * Add a new illustration
     * @return void
     */
	public function getNewBlog()
	{

    return view('master-box.admin.content.blog.new');

	}

    /**
     * Add a new illustration (datas)
     * @return void
     */
	public function postNewBlog()
	{

		// New article rules
		$rules = [

			'title' => 'required|min:5',
			'content' => 'required|min:5',
			'url' => '',

			'thumbnail' => 'required|image'

			];

		$fields = Request::all();

		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

			$blog_article = new BlogArticle;

			$blog_article->title = $fields['title'];
			$blog_article->content = $fields['content'];
			$blog_article->url = $fields['url'];
			$blog_article->user()->associate(Auth::user());

			// We manage the thumbnail
			$file = Request::file('thumbnail');
			$destinationPath = 'public/uploads/blog/';

			$filename = value(function() use ($file, $blog_article) {

				$filename = Str::slug($blog_article->title) . '.' . $file->getClientOriginalExtension();
				return $filename;

			});

			Request::file('thumbnail')->move($destinationPath, $filename);

			// We remove public for the array
			//$destinationPath = str_replace('public/', '', $destinationPath);

			$thumbnail = ['folder' => 'blog', 'filename' => $filename];
			$blog_article->thumbnail = json_encode($thumbnail);

			$blog_article->save();

			return redirect()->to('/admin/content#blog')
			->with('message', 'Nouveau article ajouté')
			->withInput();

		} else {

			// We return the same page with the error and saving the input datas
			return redirect()->back()
			->withInput()
			->withErrors($validator);

		}


	}


	/**
	 * We edit a page
	 * @return redirect
	 */
	public function postEditPage()
	{

		$fields = Request::all();
		$rules = []; // Dynamic rule

		foreach ($fields as $label => $value) {

			$rules[$label] = 'required';

		}

		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

			foreach ($fields as $label => $value) {

				$page = Page::where('slug', $label)->first();

				if ($page !== NULL) {

					//addLogAuth::user()->id, "La page `$page->title` a été modifiée");
					
					$page->content = $value;
					$page->save();

				}

			}

			session()->flash('message', "Vos pages ont correctement été mises à jour");
			return redirect()->back();

		} else {

			// We return the same page with the error and saving the input datas
			return redirect()->back()
			->withInput()
			->withErrors($validator);

		}


	}


	/**
	 * We remove the illustration
	 */
	public function getDeleteIllustration($id)
	{

		$image_article = ImageArticle::find($id);

		if ($image_article !== NULL)
		{

			$image_article->delete();

			session()->flash('message', "L'illustration a été correctement supprimée");
			return redirect()->back();


		}

	}

	/**
	 * We a edit an illustration
	 */
	public function getEditIllustration($id)
	{

		$image_article = ImageArticle::findOrFail($id);
			
    return view('master-box.admin.content.illustrations.edit')->with(compact(
      'image_article'
    ));

	}

	public function postEditIllustration()
	{

		// New article rules
		$rules = [

			'image_article_id' => 'required|integer',
			'title' => 'required|min:5',
			'slug' => 'required',
			'description' => 'required|min:5',

			'image' => 'image'

			];


		$fields = Request::all();

		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

		  $image_article = ImageArticle::findOrFail($fields['image_article_id']);
			

			$image_article->title = $fields['title'];
			$image_article->slug = $fields['slug'];
			$image_article->description = $fields['description'];
			$image_article->user()->associate(Auth::user());

			if ( ! empty($fields['image'])) {
			 $image_article->image = $this->_prepare_image($fields, $image_article);
			}

			$image_article->save();


			return redirect()->to('/admin/content#illustrations')
			->with('message', 'L\'illustration à été ajouté avec succès')
			->withInput();

		} else {

			// We return the same page with the error and saving the input datas
			return redirect()->back()
			->withInput()
			->withErrors($validator);

		}



	}

    /**
     * Add a new illustration
     * @return void
     */
	public function getNewIllustration()
	{

		return view('master-box.admin.content.illustrations.new');

	}

    /**
     * Add a new illustration (datas)
     * @return void
     */
	public function postNewIllustration()
	{


		// New article rules
		$rules = [

			'title' => 'required|min:5',
			'description' => 'required|min:5',

			'image' => 'required|image'

			];


		$fields = Request::all();

		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

			$image_article = new ImageArticle;

			$image_article->user()->associate(Auth::user());

			$image_article->title = $fields['title'];
			$image_article->description = $fields['description'];
			$image_article->image = $this->_prepare_image($fields, $image_article);

			$image_article->save();

			return redirect()->to('/admin/content#illustrations')
			->withInput();

		} else {

			// We return the same page with the error and saving the input datas
			return redirect()->back()
			->withInput()
			->withErrors($validator);

		}


	}

	private function _prepare_image($fields, $image_article)
	{


		// We manage the image
		$file = Request::file('image');
		$destinationPath = 'public/uploads/illustrations/';

		$filename = value(function() use ($file, $image_article) {

			$filename = Str::slug($image_article->title) . '.' . $file->getClientOriginalExtension();
			return $filename;

		});

		Request::file('image')->move($destinationPath, $filename);

		// We remove public for the array
		//$destinationPath = str_replace('public/', '', $destinationPath);

		$image = ['folder' => 'illustrations', 'filename' => $filename];

		return json_encode($image);

	}

}