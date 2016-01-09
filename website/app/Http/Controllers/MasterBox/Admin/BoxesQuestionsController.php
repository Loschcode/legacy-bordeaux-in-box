<?php namespace App\Http\Controllers\MasterBox\Admin;

use App\Http\Controllers\MasterBox\BaseController;

use Request, Validator;

use App\Models\Box;
use App\Models\BoxQuestion;
use App\Models\BoxAnswer;

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

    }
    
    /**
     * Get the listing page of the blog
     * @return void
     */
	public function getFocus($id)
	{

		$box = Box::findOrFail($id);

		$questions = $box->questions()->orderBy('position', 'asc')->get();

		return view('master-box.admin.boxes.questions.index')->with(compact(
      'questions',
      'box'
    ));

	}

	/**
	 * We a edit a box
	 */
	public function getEdit($id)
	{

		$question = BoxQuestion::findOrFail($id);

		$box = $question->box()->first();
		$position_listing = $this->_generate_position_listing($box, 1); // No incrementation

		return view('master-box.admin.boxes.questions.edit')->with(compact(
      'question',
      'position_listing'
    ));
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

		$fields = Request::all();
		$validator = Validator::make($fields, $rules);

    if ($validator->fails()) {

      // We return the same page with the error and saving the input datas
      return redirect()->back()
        ->withInput()
        ->withErrors($validator);
    }

    $box_question = BoxQuestion::findOrFail($fields['question_id']);

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

		session()->flash('message', "La question a bien été mise à jour");
		
    return redirect()->to('/admin/boxes/questions/focus/'.$box_question->box()->first()->id)
      ->withInput();

	}

    /**
     * Add a new question
     * @return void
     */
	public function getNew($id)
	{

		$box = Box::findOrFail($id);

		$position_listing = $this->_generate_position_listing($box, 2); // Incrementation +1

		return view('master-box.admin.boxes.questions.new')->with(compact(
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


		$fields = Request::all();

		$validator = Validator::make($fields, $rules);

    if ($validator->fails()) {

      // We return the same page with the error and saving the input datas
      return redirect()->back()
        ->withInput()
        ->withErrors($validator);
    }

    $box_question = new BoxQuestion;

	 	$box = Box::findOrFail($fields['box_id']);

		$box_question->question = $fields['question'];
		$box_question->short_question = $fields['short_question'];
		$box_question->slug = $fields['slug'];

		if ($fields['filter_must_match'] === '0') $box_question->filter_must_match = FALSE;
		else $box_question->filter_must_match = TRUE;
			
		$box_question->type = $fields['type'];
		$box_question->position = $fields['position'];
		$box_question->box()->associate($box);
    
    $box_question->save();

		session()->flash('message', "La question a bien été ajoutée à la box");
		
    return redirect()->to('/admin/boxes/questions/focus/'.$box->id)
		  ->withInput();

	}

	/**
	 * We remove the illustration
	 */
	public function getDelete($id)
	{
		$question = BoxQuestion::findOrFail($id);

    $question->delete();

		session()->flash('message', "Cette question a été archivée");
		return redirect()->back();
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