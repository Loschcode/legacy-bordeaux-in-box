<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoxQuestion extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'box_questions';

  /**
   * Create / Update
   */
  public static function boot()
    {

        parent::boot();

        static::deleting(function($box_question)
        {

          // We don't forget to reset the different positions
          $box_questions = BoxQuestion::where('id', '!=', $box_question->id)->orderBy('position', 'asc')->get();
          $num = 1;
          foreach ($box_questions as $box_question) {
            $box_question->position = $num;
            $box_question->save();
            $num++;
          }

        });

    }

	/**
	 * HasMany
	 */
	
	public function customer_answers()
	{

		return $this->hasMany('App\Models\BoxQuestionCustomerAnswer');

	}

	public function answers()
	{

		return $this->hasMany('App\Models\BoxAnswer');

	}

}