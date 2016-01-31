<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BoxQuestionCustomerAnswer extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'box_question_customer_answers';

	/**
	 * Create / Update
	 */
	public static function boot()
    {

        parent::boot();

        static::creating(function($customer_answer)
        {

        	if (empty($customer_answer->slug))
        	{
           	  $customer_answer->slug = Str::slug($customer_answer->answer);

       		}

        });

        static::updating(function($customer_answer)
        {

        	if (empty($customer_answer->slug))
        	{

           	$customer_answer->slug = Str::slug($customer_answer->answer);

       		}

        });

    }

	/**
	 * Belongs To
	 */
	
  public function referent()
  {

    return $this->belongsTo('App\Models\BoxQuestionCustomerAnswer', 'referent_id');

  }


	public function profile()
	{

		return $this->belongsTo('App\Models\CustomerProfile', 'customer_profile_id');

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

    return $this->hasMany('App\Models\BoxQuestionCustomerAnswer', 'referent_id');

  }
  
}