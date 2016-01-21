<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerOrderPreference extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'customer_order_preferences';

	/**
	 * Belongs To
	 */
	
	public function customer_profile()
	{

		return $this->belongsTo('App\Models\CustomerProfile', 'customer_profile_id');

	}

	
	public function delivery_spot()
	{

		return $this->belongsTo('App\Models\DeliverySpot', 'delivery_spot_id');

	}

	/**
	 * Fields
	 */
	public function totalPricePerMonth()
	{

		return number_format((float) $this->unity_price + $this->delivery_fees, 2, '.', '');

	}

  public function totalPricePerMonthInCents()
  {

    return round((float) $this->unity_price + $this->delivery_fees, 2) * 100;

  }

  public function isGift()
  {
  	return $this->gift;
  }

}