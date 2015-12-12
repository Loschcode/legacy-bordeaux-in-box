<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class BoxQuestion extends Model {

  use SoftDeletingTrait;

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

          // We also delete the advanced filter that's linked to it -> we will use softDeleting trait at the end
          /*$user_answers = UserAnswer::where('box_question_id', '=', $box_question->id)->get();
          foreach ($user_answers as $user_answer) {
            $user_answer->delete();
          }*/

          // We don't forget to reset the different positions
          $box_questions = BoxQuestion::where('id', '!=', $box_question->id)->orderBy('position', 'asc')->get();
          $num = 1;
          foreach ($box_questions as $box_question) {
            $box_question->position = $num;
            $box_question->save();
            $num++;
          }

          // We also delete the advanced filter that's linked to it
          $product_filter_box_answers = ProductFilterBoxAnswer::where('box_question_id', '=', $box_question->id)->get();
          foreach ($product_filter_box_answers as $product_filter_box_answer) {
            $product_filter_box_answer->delete();
          }

        });

    }

	/**
	 * Belongs To
	 */
	
	public function box()
	{

		return $this->belongsTo('Box', 'box_id');

	}

	/**
	 * HasMany
	 */
	
	public function user_answers()
	{

		return $this->hasMany('UserAnswer');

	}

	public function answers()
	{

		return $this->hasMany('BoxAnswer');

	}

}