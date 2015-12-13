<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;

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
     * The layout that should be used for responses.
     */
    protected $layout = 'layouts.admin';

    /**
     * Get the listing page of the blog
     * @return void
     */
	public function getFocus($id)
	{

		$question = BoxQuestion::find($id);
		$box = $question->box()->first();

		if ($question !== NULL) {

			$answers = $question->answers()->orderBy('created_at', 'desc')->get();

  		return view('admin.boxes.questions.answers.index')->with(compact(
        'answers',
        'question',
        'box'
      ));

		}

	}

	/**
	 * We a edit a box
	 */
	public function getEdit($id)
	{

		$answer = BoxAnswer::find($id);
		$question = $answer->question()->first();

		if ($answer !== NULL) {

			return view('admin.boxes.questions.answers.edit')->with(compact(
        'answer',
        'question'
      ));

		}


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

	 		$question_answer = BoxAnswer::find($fields['answer_id']);
			if ($question_answer === NULL) return Response::error(404);

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

		$question = BoxQuestion::find($id);
		if ($question === NULL) return Response::error(404);

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

	 		$question = BoxQuestion::find($fields['question_id']);
			if ($question === NULL) return Response::error(404);

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

		$answer = BoxAnswer::find($id);

		if ($answer !== NULL) {

			$answer->delete();

			Session::flash('message', "Cette réponse a été archivée");
			return Redirect::back();

		}

	}

}