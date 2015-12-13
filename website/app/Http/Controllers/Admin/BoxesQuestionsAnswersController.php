<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;

use App\Models\BoxQuestion;
use App\Models\BoxAnswer;

class BoxesQuestionsAnswersController extends BaseController {

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
     * Get the listing page of the blog
     * @return void
     */
	public function getFocus($id)
	{

		$question = BoxQuestion::findOrFail($id);
		$box = $question->box()->first();

		$answers = $question->answers()->orderBy('created_at', 'desc')->get();

  	return view('admin.boxes.questions.answers.index')->with(compact(
      'answers',
      'question',
      'box'
    ));

	}

	/**
	 * We a edit a box
	 */
	public function getEdit($id)
	{

		$answer = BoxAnswer::findOrFail($id);
		$question = $answer->question()->first();

    return view('admin.boxes.questions.answers.edit')->with(compact(
      'answer',
      'question'
    ));
	}

	public function postEdit()
	{

		// New article rules
		$rules = [

			'answer_id' => 'required|integer',
			'content' => 'required',

			];

		$fields = Input::all();
		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

	 		$question_answer = BoxAnswer::findOrFail($fields['answer_id']);

			$question_answer->content = $fields['content'];

			$question_answer->save();

			Session::flash('message', "La réponse a bien été mise à jour");
			return Redirect::to('/admin/boxes/questions/answers/focus/'.$question_answer->question()->first()->id)
			->withInput();

		} else {

			// We return the same page with the error and saving the input datas
			return Redirect::back()
			->withInput()
			->withErrors($validator);

		}



	}

    /**
     * Add a new box
     * @return void
     */
	public function getNew($id)
	{

		$question = BoxQuestion::findOrFail($id);

		return view('admin.boxes.questions.answers.new')->with(compact(
      'question'
    ));

	}

    /**
     * Add a new answer (datas)
     * @return void
     */
	public function postNew()
	{
		// New article rules
		$rules = [

			'question_id' => 'required|integer',
			'content' => 'required',

			];


		$fields = Input::all();

		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

			$question_answer = new BoxAnswer;

	 		$question = BoxQuestion::findOrFail($fields['question_id']);

			$question_answer->content = $fields['content'];
			$question_answer->question()->associate($question);

			$question_answer->save();

			Session::flash('message', "La réponse a bien été ajoutée à la question");
			return Redirect::to('/admin/boxes/questions/answers/focus/'.$question->id)
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

		$answer = BoxAnswer::findOrFail($id);
    $answer->delete();

		Session::flash('message', "Cette réponse a été archivée");
		return Redirect::back();

	}

}