<?php namespace App\Models;

class UserAnswer extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_answers';

	/**
	 * Create / Update
	 */
	public static function boot()
    {

        parent::boot();

        static::creating(function($user_answer)
        {

        	if (empty($user_answer->slug))
        	{

           		$user_answer->slug = Str::slug($user_answer->answer);

       		}

        });

        static::updating(function($user_answer)
        {

        	if (empty($user_answer->slug))
        	{

           		$user_answer->slug = Str::slug($user_answer->answer);

       		}

        });

    }

	/**
	 * Belongs To
	 */
	
  public function referent()
  {

    return $this->belongsTo('UserAnswer', 'referent_id');

  }


	public function profile()
	{

		return $this->belongsTo('UserProfile', 'user_profile_id');

	}

	public function box_question()
	{

		return $this->belongsTo('BoxQuestion', 'box_question_id');

	}

  /**
   * Has Many
   */
  
  public function children()
  {

    return $this->hasMany('UserAnswer', 'referent_id');

  }
  
}