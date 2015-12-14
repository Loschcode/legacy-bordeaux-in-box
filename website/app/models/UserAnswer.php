<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model {

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

    return $this->belongsTo('App\Models\UserAnswer', 'referent_id');

  }


	public function profile()
	{

		return $this->belongsTo('App\Models\UserProfile', 'user_profile_id');

	}

	public function box_question()
	{

		return $this->belongsTo('App\Models\BoxQuestion', 'box_question_id');

	}

  /**
   * Has Many
   */
  
  public function children()
  {

    return $this->hasMany('App\Models\UserAnswer', 'referent_id');

  }
  
}