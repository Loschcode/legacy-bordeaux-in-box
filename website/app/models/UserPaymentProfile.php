<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class UserPaymentProfile extends Eloquent {

	use SoftDeletingTrait;

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

		return $this->belongsTo('UserProfile', 'user_profile_id');

	}

}