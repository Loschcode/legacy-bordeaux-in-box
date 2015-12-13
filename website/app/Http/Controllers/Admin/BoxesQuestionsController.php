<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;

class BoxesQuestionsController extends BaseController {

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
      $this->middleware('isAdmin');

    }
    
	/**
     * The layout that should be used for responses.
     */
    protected $layout = 'layouts.admin';

    /**
     * Get the listing page of the blog
     * @return void
     */
	public function getFocus($id)
	{

		$box = Box::find($id);

		if ($box !== NULL) {

			$questions = $box->questions()->orderBy('position', 'asc')->get();

		  return view('admin.boxes.questions.index')->with(compact(
        'questions',
        'box'
      ));

		}

	}

	/**
	 * We a edit a box
	 */
	public function getEdit($id)
	{

		$question = BoxQuestion::find($id);

		if ($question !== NULL) {

			$box = $question->box()->first();
			$position_listing = $this->_generate_position_listing($box, 1); // No incrementation

			return view('admin.boxes.questions.edit')->with(compact(
        'question',
        'position_listing'
      ));

		}


	}

	public function postEdit()
	{

		// New article rules
		$rules = [

			'question_id' => 'required|integer',
			'question' => 'required|min:5',
			'short_question' => 'required|max:15',
			'filter_must_match' => 'required|integer',
			'slug' => '',
			'type' => 'required|not_in:0',
			'position' => 'required|not_in:0',

			];

		$fields = Input::all();

		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

	 		$box_question = BoxQuestion::find($fields['question_id']);
			if ($box_question === NULL) return Response::error(404);

			$box_question->question = $fields['question'];
			$box_question->short_question = $fields['short_question'];
			$box_question->slug = $fields['slug'];

			if ($fields['filter_must_match'] === '0') $box_question->filter_must_match = FALSE;
			else $box_question->filter_must_match = TRUE;

			$box_question->type = $fields['type'];
			$box_question->position = $fields['position'];

			// If this question type can't have any answer we remove
			// The old answer (archive it) just in case there are some
			if (has_no_answer_possible($box_question->type)) {

				BoxAnswer::where('box_question_id', $box_question->id)->delete();

			}

			$box_question->save();

			Session::flash('message', "La question a bien été mise à jour");
			return Redirect::to('/admin/boxes/questions/focus/'.$box_question->box()->first()->id)
			->withInput();

		} else {

			// We return the same page with the error and saving the input datas
			return Redirect::back()
			->withInput()
			->withErrors($validator);

		}



	}

    /**
     * Add a new question
     * @return void
     */
	public function getNew($id)
	{

		$box = Box::find($id);
		if ($box === NULL) return Response::error(404);

		$position_listing = $this->_generate_position_listing($box, 2); // Incrementation +1

		return view('admin.boxes.questions.new')->with(compact(
      'box',
      'position_listing'
    ));

	}

    /**
     * Add a new question
     * @return void
     */
	public function postNew()
	{

		// New article rules
		$rules = [

			'box_id' => 'required|integer',
			'question' => 'required|min:5',
			'short_question' => 'required|max:15',
			'filter_must_match' => 'required|integer',
			'slug' => '',
			'type' => 'required|not_in:0',
			'position' => 'required|not_in:0',

			];


		$fields = Input::all();

		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

			$box_question = new BoxQuestion;

	 		$box = Box::find($fields['box_id']);
			if ($box === NULL) return Response::error(404);

			$box_question->question = $fields['question'];
			$box_question->short_question = $fields['short_question'];
			$box_question->slug = $fields['slug'];

			if ($fields['filter_must_match'] === '0') $box_question->filter_must_match = FALSE;
			else $box_question->filter_must_match = TRUE;
			
			$box_question->type = $fields['type'];
			$box_question->position = $fields['position'];
			$box_question->box()->associate($box);

			$box_question->save();

			Session::flash('message', "La question a bien été ajoutée à la box");
			return Redirect::to('/admin/boxes/questions/focus/'.$box->id)
			->withInput();

		} else {

			// We return the same page with the error and saving the input datas
			return Redirect::back()
			->withInput()
			->withErrors($validator);

		}


	}

	/**
	 * We remove the illustration
	 */
	public function getDelete($id)
	{

		$question = BoxQuestion::find($id);

		if ($question !== NULL) {

			$question->delete();

			Session::flash('message', "Cette question a été archivée");
			return Redirect::back();
      
		}

	}

	private function _generate_position_listing($box, $inc=0)
	{

		$num_questions = $box->questions()->count() + $inc;
		$final_array = ['0' => '-'];

		$num = 1;

		while ($num < $num_questions) {

			$final_array[$num] = $num;
			$num++;

		}

		return $final_array;

	}

}