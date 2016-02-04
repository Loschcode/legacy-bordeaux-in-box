<?php namespace App\Http\Controllers\MasterBox\Admin;

use App\Http\Controllers\MasterBox\BaseController;

use Request, Validator;

use App\Models\Box;
use App\Models\BoxQuestion;
use App\Models\BoxAnswer;

class BoxQuestionsController extends BaseController {

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

		$questions = BoxQuestion::orderBy('position', 'asc')->get();

		return view('masterbox.admin.box.questions.index')->with(compact(
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

		$position_listing = $this->_generate_position_listing(1); // No incrementation

		return view('masterbox.admin.box.questions.edit')->with(compact(
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
      'question_gift' => 'required|min:5',
      'only_gift' => 'required',
			'short_question' => 'required|max:15',
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
    $box_question->question_gift = $fields['question_gift'];
		$box_question->short_question = $fields['short_question'];
		$box_question->slug = $fields['slug'];
    $box_question->only_gift = $fields['only_gift'];

		$box_question->type = $fields['type'];
		$box_question->position = $fields['position'];

		// If this question type can't have any answer we remove
		// The old answer (archive it) just in case there are some
		if (has_no_answer_possible($box_question->type)) {
      
      BoxAnswer::where('box_question_id', $box_question->id)->delete();

		}

		$box_question->save();
    $this->update_box_questions_positions();

		session()->flash('message', "La question a bien été mise à jour");
		
    return redirect()->action('MasterBox\Admin\BoxQuestionsController@getIndex');

	}

    /**
     * Add a new question
     * @return void
     */
	public function getNew()
	{

		$position_listing = $this->_generate_position_listing(2); // Incrementation +1

		return view('masterbox.admin.box.questions.new')->with(compact(
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

			'question' => 'required|min:5',
      'question_gift' => 'required|min:5',
			'short_question' => 'required|max:15',
      'only_gift' => 'required',
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

		$box_question->question = $fields['question'];
    $box_question->question_gift = $fields['question_gift'];
		$box_question->short_question = $fields['short_question'];
		$box_question->slug = $fields['slug'];
    $box_question->only_gift = $fields['only_gift'];
    
		$box_question->type = $fields['type'];
		$box_question->position = $fields['position'];
    
    $box_question->save();
    $this->update_box_questions_positions();

		session()->flash('message', "La question a bien été ajoutée à la box");
		
    return redirect()->action('MasterBox\Admin\BoxQuestionsController@getIndex');

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

  private function update_box_questions_positions() {

    $box_questions = BoxQuestion::orderBy('position', 'asc')->orderBy('updated_at', 'desc')->get();
    $num = 1;
    foreach ($box_questions as $box_question) {
      $box_question->position = $num;
      $box_question->save();
      $num++;
    }

  }

	private function _generate_position_listing($inc=0)
	{

		$num_questions = BoxQuestion::count() + $inc;
		$final_array = ['0' => '-'];

		$num = 1;

		while ($num < $num_questions) {

			$final_array[$num] = $num;
			$num++;

		}

		return $final_array;

	}

}