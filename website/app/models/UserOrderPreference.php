<?php namespace App\Models;

class UserOrderPreference extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_order_preferences';

	/**
	 * Belongs To
	 */
	
	public function user_profile()
	{

		return $this->belongsTo('UserProfile', 'user_profile_id');

	}

	
	public function delivery_spot()
	{

		return $this->belongsTo('DeliverySpot', 'delivery_spot_id');

	}

	/**
	 * Fields
	 */
	public function totalPricePerMonth()
	{

		return number_format((float) $this->unity_price + $this->delivery_fees, 2, '.', '');

	}

}