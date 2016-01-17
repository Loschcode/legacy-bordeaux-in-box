<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model {

	use SoftDeletes;

    protected $dates = ['deleted_at', 'status_updated_at'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'customer_profiles';

	/**
	 * Create / Update
	 */
	public static function boot()
    {

        parent::boot();

        static::creating(function($profile)
        {

        	if ($profile->status_updated_at == NULL)
        	{

           		$profile->status_updated_at = time();

       		}

        });

        static::updating(function($profile)
        {

        	// $profile['attributes'] = new one
        	// $profile['original'] = old one

        	// If it's not the same than the old one
        	if ($profile['attributes']['status'] != $profile['original']['status'])
        	{

           		$profile->status_updated_at = date('Y-m-d h:i:s', time());

       		}

        });

    }

	/**
	 * Belongs To
	 */

	public function customer()
	{

		return $this->belongsTo('App\Models\Customer', 'customer_id');

	}

	/**
	 * HasOne
	 */


	public function order_preference()
	{

		return $this->hasOne('App\Models\CustomerOrderPreference');

	}

	public function payment_profile()
	{

		return $this->hasOne('App\Models\CustomerPaymentProfile');

	}

	public function order_building()
	{

		return $this->hasOne('App\Models\CustomerOrderBuilding');

	}

	/**
	 * HasMany
	 */

	public function answers()
	{

		return $this->hasMany('App\Models\BoxQuestionCustomerAnswer');

	}

	public function customer_profile_products()
	{

		return $this->hasMany('App\Models\CustomerProfileProduct');

	}

	public function orders()
	{

		return $this->hasMany('App\Models\Order');

	}

	public function notes()
	{

		return $this->hasMany('App\Models\CustomerProfileNote');

	}

	public function payments()
	{

		return $this->hasMany('App\Models\Payment');

	}

	/**
	 * Others
	 */

	/**
	 * Get the answer of a question from a form the user has taken (usually for a box)
	 * @return object
	 */
	public function getAnswer($slug, $with_trashed=FALSE)
	{

		if ($with_trashed) $focus_question = $this->box()->first()->questions()->withTrashed()->where('slug', '=', $slug)->first();
		else $focus_question = $this->box()->first()->questions()->where('slug', '=', $slug)->first();

		if ($focus_question == NULL) return $focus_question;

		$customer_answers = $this->answers()->where('box_question_id', '=', $focus_question->id)->where('customer_profile_id', '=', $this->id)->get();

		if ($customer_answers->count() > 1) {

			$total_answers = '';
			foreach ($customer_answers->get() as $answer) {

				$total_answers .= $answer->answer . ' / ';
			}

			return $total_answers;

		} else {

			if ($customer_answers->first() == NULL) return '';

			return $customer_answers->first()->answer;

		}

	}

	/**
	 * Check if the user is sponsor or has a sponsor
	 * @return  boolean [description]
	 */
	public function isOrHasSponsor()
	{

		$sponsor = $this->getAnswer('sponsor');
		if (!empty($sponsor)) {

			return TRUE;

		}

		$is_sponsor_of = $this->isSponsorOf();
		if ($is_sponsor_of > 0) return TRUE;

		return FALSE;

	}

	/**
	 * Check if the user is sponsor
	 * @return  boolean [description]
	 */
	public function hasSponsor()
	{

		$sponsor = $this->getAnswer('sponsor');
		if (!empty($sponsor)) {

			return TRUE;

		}

		return FALSE;

	}

	/**
	 * Check if the user is sponsor
	 * @return  boolean [description]
	 */
	public function isSponsor()
	{

		$is_sponsor_of = $this->isSponsorOf();

		if ($is_sponsor_of > 0) return TRUE;

		return FALSE;

	}

	/**
	 * Calculate the number of users the user is sponsor of
	 * @return integer
	 */
	public function isSponsorOf() {

		$email = $this->customer()->first()->email;

		$box_question = BoxQuestion::where('slug', '=', 'sponsor')->first();

		if ($box_question === NULL)
		{
			return 0;
		}
		return UserAnswer::where('box_question_id', '=', $box_question->id)->where('slug', '=', Str::slug($email))->count();

	}

	public function getSeriesProfileProduct($serie_id) {

  	return $this->seriesProfileProduct($serie_id)->get();

	}

	public function seriesProfileProduct($serie_id) {

		return $this->customer_profile_products()
  	->join('serie_products', 'customer_profile_products.serie_product_id', '=', 'serie_products.id')
  	->where('serie_products.delivery_serie_id', '=', $serie_id)
  	->select('customer_profile_products.*')
  	->groupBy('customer_profile_products.id');

	}

	/**
	 * We get the exact age of the person from his `birthday` (getAnswer)
	 * @return integer
	 */
	public function getAge()
	{
		$date_birthday = $this->getAnswer('birthday');
		$date_birthday = convert_date_to_age($date_birthday);
		return $date_birthday;

	}

	/**
	 * Get the total of not subscribed profile (not linked with any box, it means the user didn't chose anything)
	 * @return object
	 */
	public static function getNotSubscribedProfiles()
	{

		return self::where('status', '=', 'not-subscribed');

	}

	/**
	 * Get the total of not half subscribed profile
	 * @return object
	 */
	public static function getInProgressProfiles()
	{

		return self::where('status', '=', 'in-progress');

	}

	/**
	 * Get the total of subscribed profile (it means they have a stripe customer id at least)
	 * @return object
	 */
	public static function getSubscribedProfiles()
	{

		return self::where('status', '=', 'subscribed');

	}

	/**
	 * Get the total of subscribed profile (it means they have a stripe customer id at least)
	 * @return object
	 */
	public static function getExpiredProfiles()
	{

		return self::where('status', '=', 'expired');

	}

	/**
	 * When a profile expires, we can call this method to send an email to the user
	 * And try to get it back
	 * @return void
	 */
	public function sendExpirationEmail($last_box_was_sent=FALSE)
	{

		$customer = $this->customer()->first();
		$box = $this->box()->first();

		$data = [

			'first_name' => $customer->first_name,
			'box_title' => $box->title,
			'last_box_was_sent' => $last_box_was_sent,

		];

		// We send the email
		mailing_send($this, "Bordeaux in Box - Ton abonnement vient d'expirer", 'emails.subscription.expired', $data, NULL);

	}

}
