<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class UserPaymentProfile extends Model {

  use SoftDeletes;

    protected $dates = ['deleted_at'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_payment_profiles';

	/**
	 * Belongs To
	 */
	
	public function profile()
	{

		return $this->belongsTo('App\Models\UserProfile', 'user_profile_id');

	}

}