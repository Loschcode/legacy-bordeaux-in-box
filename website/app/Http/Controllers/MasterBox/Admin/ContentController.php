<?php namespace App\Http\Controllers\MasterBox\Admin;

use App\Http\Controllers\MasterBox\BaseController;

use App\Models\BlogArticle;
use App\Models\ImageArticle;
use App\Models\Page;

use Request, Validator, Auth, Str;

class ContentController extends BaseController {


 	/**
	 * Filters
   */
	public function __construct()
  {
		$this->beforeMethod();
	}
    

	/**
	 * Display articles of the blog
	 * @return void
	 */
	public function getBlog()
	{
		$blog_articles = BlogArticle::orderBy('created_at', 'desc')->get();

		return view('masterbox.admin.content.blog.index')->with(compact(
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

		return view('masterbox.admin.content.blog.edit')->with(compact(
      'blog_article'
    ));
	}

	/**
	 * Manage edit blog article
	 */
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
			$blog_article->customer()->associate(Auth::guard('customer')->user());
			
			if ( ! empty($fields['thumbnail']))
			{

				// We manage the thumbnail
				$file = Request::file('thumbnail');
				$destinationPath = public_path('uploads/blog/');

				$filename = value(function() use ($file, $blog_article) {

					$filename = Str::slug($blog_article->title) . '.' . $file->getClientOriginalExtension();
					return $filename;

				});

				Request::file('thumbnail')->move($destinationPath, $filename);

				$thumbnail = ['folder' => 'blog', 'filename' => $filename];
				$blog_article->thumbnail = json_encode($thumbnail);

			}

			$blog_article->save();

			return redirect()->to('/admin/content/blog')
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
		return view('masterbox.admin.content.blog.new');
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
			$blog_article->customer()->associate(Auth::guard('customer')->user());

			// We manage the thumbnail
			$file = Request::file('thumbnail');
			$destinationPath = 'uploads/blog/';

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

			session()->flash('message', 'Nouvel article ajouté');

			return redirect()->to('/admin/content/blog')
			->withInput();

		} else {

			// We return the same page with the error and saving the input datas
			return redirect()->back()
			->withInput()
			->withErrors($validator);

		}


	}

	/**
	 * Display pages, you can directly edit them
	 * @return void
	 */
	public function getPages()
	{
	  $pages = Page::get();

	  return view('masterbox.admin.content.pages.index')->with(compact(
	  	'pages'
	  ));
	}

	/**
	 * Display the form to edit a page
	 * @param  string $id Id of the page
	 */
	public function getEditPage($id)
	{

		$page = Page::findOrFail($id);

		return view('masterbox.admin.content.pages.edit')->with(compact(
			'page'
		));

	}

	/**
	 * Handle the form edit page
	 */
	public function postEditPage()
	{

		// Fetch inputs
		$inputs = Request::all();

		// Fetch page or stop the process
		$page = Page::findOrFail($inputs['page_id']);

		// Content is Required
		$validator = Validator::make($inputs, ['content' => 'required']);

		// The form validation was good
		if ($validator->passes()) {

			// Update page
			$page->content = $inputs['content'];
			$page->save();

			return redirect()->back()->with('message', 'La page à bien été édité');

		}

		return redirect()->back()
		->withInput()
		->withErrors($validator);

	}

	/**
	 * Display illustrations
	 * @return void
	 */
	public function getIllustrations()
	{
		$image_articles = ImageArticle::orderBy('created_at', 'desc')->get();

		return view('masterbox.admin.content.illustrations.index')->with(compact(
			'image_articles'
		));

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
			
    return view('masterbox.admin.content.illustrations.edit')->with(compact(
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
			$image_article->customer()->associate(Auth::guard('customer')->user());

			if ( ! empty($fields['image'])) {
			 $image_article->image = $this->_prepare_image('image', $image_article, 'illustrations');
			}

			$image_article->save();

			return redirect()->action('MasterBox\Admin\ContentController@getIllustrations')
			->with('message', 'L\'illustration à été édité avec succès')
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

		return view('masterbox.admin.content.illustrations.new');

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

			$image_article->customer()->associate(Auth::guard('customer')->user());

			$image_article->title = $fields['title'];
			$image_article->description = $fields['description'];
			$image_article->image = $this->_prepare_image('image', $image_article, 'illustrations');

			$image_article->save();

			return redirect()->to('/admin/content/illustrations')
			->with('message', "L'illustration a été ajouté")
			->withInput();

		} else {

			// We return the same page with the error and saving the input datas
			return redirect()->back()
			->withInput()
			->withErrors($validator);

		}


	}

	private function _prepare_image($field_name, $image_article, $path)
	{

		// We manage the image
		$file = Request::file($field_name);
		$destinationPath = public_path('uploads/'.$path.'/');

		$filename = value(function() use ($file, $image_article) {

			$filename = time() . '-' . Str::slug($image_article->title) . '.' . $file->getClientOriginalExtension();
			return $filename;

		});

    delete_file($path, $filename);

		Request::file($field_name)->move($destinationPath, $filename);

		$image = ['folder' => $path, 'filename' => $filename];

		return json_encode($image);

	}

}