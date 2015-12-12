<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoxAnswer extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'box_answers';

	/**
	 * Belongs To
	 */
	
	public function question()
	{

		return $this->belongsTo('BoxQuestion', 'box_question_id');

	}

}