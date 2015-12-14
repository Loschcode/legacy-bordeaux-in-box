<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model {

	use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'orders';

	/**
	 * Belongs To
	 */
	
	public function user()
	{

		return $this->belongsTo('App\Models\User', 'user_id');

	}

	public function user_profile()
	{

		return $this->belongsTo('App\Models\UserProfile', 'user_profile_id');

	}

	public function box()
	{

		return $this->belongsTo('App\Models\Box', 'box_id');

	}

	public function delivery_serie()
	{

		return $this->belongsTo('App\Models\DeliverySerie', 'delivery_serie_id');

	}


	public function delivery_spot()
	{

		return $this->belongsTo('App\Models\DeliverySpot', 'delivery_spot_id');

	}

	/*public function payment()
	{

		return $this->belongsTo('Payment', 'payment_id');

	}*/


	/**
	 * HasMany
	 */
	
	public function user_profile_products()
	{

		return $this->hasMany('App\Models\UserProfileProduct');

	}

	public function payments()
	{

		return $this->hasMany('App\Models\Payment');

	}
	
	public function destination()
	{

		return $this->hasOne('App\Models\OrderDestination');

	}

	public function billing()
	{

		return $this->hasOne('App\Models\OrderBilling');

	}

	public function scopeLockedOrdersWithoutOrder($query)
	{
		return $query->where('locked', TRUE)->whereNull('date_sent');

	}

	/**
	 * Other
	 */
	
	public function HT_UnityAndFeesPrice() {

		return round($this->unity_and_fees_price * 0.80, 2);

	}
	
	/**
	 * We will try to find the zip from the destination, or the billing, or the user himself
	 * This can't theorically fails, it should work at first because we don't consider the take aways
	 * This has been done for isRegionalOrder ; if we have to go to the user
	 * It means the entry has been changed manually within the database
	 * @return [type] [description]
	 */
	public function findOutZip() {

		$destination = $this->destination()->first();

		if ($destination === NULL) {

			$billing = $this->billing()->first();

			if ($billing === NULL) {

				$destination_zip = $this->user_profile()->first()->user()->first()->zip;

			} else {

				$destination_zip = $billing->zip;

			}

		} else {

			$destination_zip = $destination->zip;

		}

		return $destination_zip;

	}
	
	// BE CAREFUL : as for the other query in this model, this sensitive system works only with spots in the region (for now)
	public function isRegionalOrder() {

		if ($this->take_away) return TRUE;
		else {

			$destination_zip = $this->findOutZip();
			$region = substr(trim($destination_zip), 0, 2);
		
			if ($region === '33') return TRUE;
			else return FALSE;

		}

	}

	public function scopeByFrequency($query, $frequency)
	{

		return $query
		->join('user_order_preferences as preferences', 'preferences.user_profile_id', '=', 'orders.user_profile_id')
		->where('preferences.frequency', '=', $frequency);
		
	}

	public function scopeActiveOrders($query) // Orders not delivered but also not canceled
	{
		
		return $query->where('orders.status', '!=', 'delivered')->where('orders.status', '!=', 'canceled');

	}

	public function scopeGetUserProfiles($query) // Orders not delivered but also not canceled
	{

		return $query->join('user_profiles', 'orders.user_profile_id', '=', 'user_profiles.id')
                ->groupBy('user_profiles.id')
                ->select('user_profiles.*');
	}

	public function scopeDeliveredOrders($query) // Orders not delivered but also not canceled
	{
		
		return $query->where('orders.status', '=', 'delivered');

	}

	public function scopeNotCanceledOrders($query)
	{

		return $query->where('orders.status', '!=', 'canceled');

	}

	// BE CAREFUL : This algorithm work only if the spots stay regional (i guess for a while considering our expansion is very slow)
	// We should change it if we grow
	public function scopeRegionalOrders($query)
	{

		return $query->join('order_destinations', 'order_destinations.order_id', '=', 'orders.id')->whereNested(function($query) {

			$query->where('order_destinations.zip', 'LIKE', '33%');
			$query->orWhere('orders.take_away', '=', true);

		});

	}

	public function scopeNotGift($query)
	{

		return $query->where('orders.gift', FALSE);

	}

	public function scopeOnlyGift($query)
	{

		return $query->where('orders.gift', TRUE);

	}

  public function scopeLockedOrders($query)
  {

  	return $query->where('orders.locked', TRUE)->whereNull('date_sent')->orderBy('delivery_serie_id', 'asc')->orderBy('created_at', 'asc');
  }

  public function scopeLockedAndPackedOrders($query)
  {

  	return $query->where('locked', TRUE)->where('status', '=', 'ready')->whereNull('date_sent')->orderBy('delivery_serie_id', 'asc')->orderBy('created_at', 'asc');

  }


}