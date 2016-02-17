<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'orders';

  protected $casts = [
      
      'take_away' => 'boolean',
      'locked' => 'boolean',
      'gift' => 'boolean',
  ];

  /**
   * Create / Update
   */
  public static function boot() {

        parent::boot();

        static::creating(function($order)
        {

        });

        static::updating(function($order)
        {

        });

        static::deleting(function($order) {

          if ($order->billing()->first() !== NULL)
            $order->billing()->first()->delete();

          if ($order->company_billing()->first() !== NULL)
            $order->company_billing()->delete();

          if ($order->destination()->first() !== NULL)
            $order->destination()->first()->delete();

          foreach ($order->order_payments()->get() as $order_payment) {

            $order_payment->delete();

          }

        });

  }

	/**
	 * Belongs To
	 */

  public function company_billing()
  {

    return $this->belongsTo('App\Models\CompanyBilling', 'company_billing_id');

  }

	public function customer()
	{

		return $this->belongsTo('App\Models\Customer', 'customer_id');

	}

  public function customer_preference()
  {

    $middle = $this->belongsTo('App\Models\CustomerProfile', 'customer_profile_id'); 
    return $middle->getResults()->hasOne('App\Models\CustomerOrderPreference'); 
  
  }

	public function customer_profile()
	{

		return $this->belongsTo('App\Models\CustomerProfile', 'customer_profile_id');

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
  
  public function order_payments()
  {

    return $this->hasMany('App\Models\OrderPayment');

  }

	public function payments()
	{

    return $this->belongsToMany('App\Models\Payment', 'order_payments');
		//return $this->hasManyThrough('App\Models\Payment', 'App\Models\OrderPayment');

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
   * Check if we have some problems with the payments of the order
   * @return boolean
   */
  public function hasProblemPayment()
  {

    if ($this->already_paid == 0) {
      return true;
    }

    return false;

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

				$destination_zip = $this->customer_profile()->first()->customer()->first()->zip;

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


  /**
   * Only the payable orders (used in the Invoice to credit orders)
   */
  public function scopeOnlyPayable($query)
  {

    return $query->where('status', '!=', 'paid')
                 ->where('status', '!=', 'delivered')
                 ->where('status', '!=', 'canceled');

  }

  /**
   * Only the refundable orders (used in the Invoice to debit orders)
   */
  public function scopeOnlyRefundable($query)
  {

    return $query->where('already_paid', '>', 0);
    //$query->where('status', '=', 'paid')
                 //->where('status', '=', 'half-paid') <--- OR WHERE IF NOT WORKING (I FORGOT AND CHANGE MTHOLOGY IN THE MIDDLE)
                 //->where('status', '=', 'delivered')
                 //->where('status', '=', 'canceled');

  }

	public function scopeByFrequency($query, $frequency)
	{

		return $query
		->join('customer_order_preferences as preferences', 'preferences.customer_profile_id', '=', 'orders.customer_profile_id')
		->where('preferences.frequency', '=', $frequency);
		
	}

	public function scopeActiveOrders($query) // Orders not delivered but also not canceled
	{
		
		return $query->where('orders.status', '!=', 'delivered')->where('orders.status', '!=', 'canceled');

	}

	public function scopeGetCustomerProfiles($query) // Orders not delivered but also not canceled
	{

		return $query->join('customer_profiles', 'orders.customer_profile_id', '=', 'customer_profiles.id')
                ->groupBy('customer_profiles.id')
                ->select('customer_profiles.*');
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

		return $query->join('order_destinations', 'order_destinations.order_id', '=', 'orders.id')
                 ->join('coordinates', 'order_destinations.coordinate_id', '=', 'coordinates.id')
                 ->whereNested(function($query) {

			$query->where('coordinates.zip', 'LIKE', '33%');
			$query->orWhere('orders.take_away', '=', true);

		});

	}

  public function scopeRegionalOrdersNotTakeAway($query)
  {

    return $query->join('order_destinations', 'order_destinations.order_id', '=', 'orders.id')
                 ->join('coordinates', 'order_destinations.coordinate_id', '=', 'coordinates.id')
                 ->whereNested(function($query) {

      $query->where('coordinates.zip', 'LIKE', '33%');
      $query->orWhere('orders.take_away', '=', false);

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