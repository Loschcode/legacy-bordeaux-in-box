<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Payment extends Eloquent {

	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'payments';

	/**
	 * Belongs To
	 */
	
	public function user()
	{

		return $this->belongsTo('User', 'user_id');

	}

	public function order()
	{

		return $this->belongsTo('Order', 'order_id');

	}
	
	public function profile()
	{

		return $this->belongsTo('UserProfile', 'user_profile_id');

	}

	/**
	 * Other
	 */
	
	public static function getTotal()
	{
		return self::where('paid', '=', true)->sum('amount');

	}
	
}