<?php namespace App\Http\Controllers\MasterBox\Admin;

use App\Http\Controllers\MasterBox\BaseController;

use App\Models\Box;

class BoxesController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Admin Boxes Controller
	|--------------------------------------------------------------------------
	|
	| Add / Edit / Delete boxes
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

		$active_boxes = Box::where('active', TRUE)->orderBy('created_at', 'desc')->get();
		$unactive_boxes = Box::where('active', FALSE)->orderBy('created_at', 'desc')->get();

		return view('master-box.admin.boxes.index')->with(compact(
      'active_boxes',
      'unactive_boxes'
    ));

	}

	/**
	 * We a edit a box
	 */
	public function getEdit($id)
	{
		$box = Box::findOrFail($id);

		return view('master-box.admin.boxes.edit')->with(compact(
      'box'
    ));
	}

	public function postEdit()
	{

		// New article rules
		$rules = [

			'box_id' => 'required|integer',
			'title' => 'required|min:5',
			'description' => 'required|min:5',
			'image' => 'image'

			];

		$fields = Request::all();
		$validator = Validator::make($fields, $rules);

    if ($validator->fails())
    {
      // We return the same page with the error and saving the input datas
      return redirect()->back()
      ->withInput()
      ->withErrors($validator);
    }


		$box = Box::findOrFail($fields['box_id']);

		$box->title = $fields['title'];
		$box->description = $fields['description'];
		$box->active = FALSE;

		if ( ! empty($fields['image'])) {
		  $box->image = $this->_prepare_image($fields, $box);
		}

		$box->save();

		return redirect()->to('/admin/boxes#offline')
		  ->with('message', 'La box à été édité avec succès')
			->withInput();


	}

    /**
     * Add a new box
     * @return void
     */
	public function getNew()
	{
		return view('master-box.admin.boxes.new');
	}

    /**
     * Add a new illustration (datas)
     * @return void
     */
	public function postNew()
	{


		// New article rules
		$rules = [

			'title' => 'required|min:5',
			'description' => 'required|min:5',
			'image' => 'required|image'

			];


		$fields = Request::all();
		$validator = Validator::make($fields, $rules);

    if ($validator->fails())
    {
      // We return the same page with the error and saving the input datas
      return redirect()->back()
        ->withInput()
        ->withErrors($validator);
    }

		$box = new Box;

		$box->title = $fields['title'];
		$box->description = $fields['description'];
		$box->image = $this->_prepare_image($fields, $box);
		$box->active = FALSE; // Every new box isn't enabled by default

		$box->save();

		return redirect()->to('/admin/boxes#offline')
      ->with('message', 'La boxe a été ajouté avec succès et a été placé dans la catégorie hors line')
      ->withInput();

	}

	private function _prepare_image($fields, $box)
	{


		// We manage the image
		$file = Request::file('image');
		$destinationPath = 'public/uploads/boxes/';

		$filename = value(function() use ($file, $box) {

			$filename = $box->slug . '.' . $file->getClientOriginalExtension();
			return $filename;

		});

		Request::file('image')->move($destinationPath, $filename);

		// We remove public for the array
		//$destinationPath = str_replace('public/', '', $destinationPath);

		$image = ['folder' => 'boxes', 'filename' => $filename];

		return json_encode($image);

	}

	/**
	 * We remove the illustration
	 */
	public function getDelete($id)
	{
		$box = Box::findOrFail($id);

		$box->delete();

		session()->flash('message', "Cette box a été archivée");
		return redirect()->back();
	}

	/**
	 * We activate the box
	 */
	public function getActivate($id)
	{

		$box = Box::findOrFail($id);

		$box->active = TRUE;
		$box->save();

		session()->flash('message', "Cette box a été activé");
		return redirect()->back();
	}

	/**
	 * We desactivate the box
	 */
	public function getDesactivate($id)
	{
		$box = Box::findOrFail($id);

    $box->active = FALSE;
		$box->save();

		session()->flash('message', "Cette box a été désactivé");
		return redirect()->back();
	}

}