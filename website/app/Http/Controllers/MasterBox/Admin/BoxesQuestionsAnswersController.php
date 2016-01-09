<?php namespace App\Http\Controllers\MasterBox\Admin;

use App\Http\Controllers\MasterBox\BaseController;

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

  	return view('master-box.admin.boxes.questions.answers.index')->with(compact(
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

    return view('master-box.admin.boxes.questions.answers.edit')->with(compact(
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

		$fields = Request::all();
		$validator = Validator::make($fields, $rules);

    if ($validator->fails())
    {
      // We return the same page with the error and saving the input datas
      return redirect()->back()
        ->withInput()
        ->withErrors($validator);
    }


	 	$question_answer = BoxAnswer::findOrFail($fields['answer_id']);

		$question_answer->content = $fields['content'];

		$question_answer->save();

		session()->flash('message', "La réponse a bien été mise à jour");

		return redirect()->to('/admin/boxes/questions/answers/focus/'.$question_answer->question()->first()->id)
		  ->withInput();
	}

    /**
     * Add a new box
     * @return void
     */
	public function getNew($id)
	{

		$question = BoxQuestion::findOrFail($id);

		return view('master-box.admin.boxes.questions.answers.new')->with(compact(
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


		$fields = Request::all();

		$validator = Validator::make($fields, $rules);

    if ($validator->fails())
    {
      // We return the same page with the error and saving the input datas
      return redirect()->back()
        ->withInput()
        ->withErrors($validator);
    }


		$question_answer = new BoxAnswer;

	 	$question = BoxQuestion::findOrFail($fields['question_id']);

		$question_answer->content = $fields['content'];
		$question_answer->question()->associate($question);

		$question_answer->save();

		session()->flash('message', "La réponse a bien été ajoutée à la question");
		
    return redirect()->to('/admin/boxes/questions/answers/focus/'.$question->id)
      ->withInput();
	}

	/**
	 * We remove the illustration
	 */
	public function getDelete($id)
	{

		$answer = BoxAnswer::findOrFail($id);
    $answer->delete();

		session()->flash('message', "Cette réponse a été archivée");
		return redirect()->back();

	}

}