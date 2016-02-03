<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPaymentProfile extends Model {

  protected $dates = ['deleted_at'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'customer_payment_profiles';

	/**
	 * Belongs To
	 */
	
	public function profile()
	{

		return $this->belongsTo('App\Models\CustomerProfile', 'customer_profile_id');

	}

}