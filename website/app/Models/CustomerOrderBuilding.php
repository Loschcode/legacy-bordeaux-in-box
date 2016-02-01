<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerOrderBuilding extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'customer_order_buildings';

	/**
	 * Belongs To
	 */
	
	public function customer()
	{

		return $this->belongsTo('App\Models\Customer', 'customer_id');

	}

	public function order_preference()
	{

		return $this->belongsTo('App\Models\CustomerOrderPreference', 'customer_order_preference_id');

	}

	public function profile()
	{

		return $this->belongsTo('App\Models\CustomerProfile', 'customer_profile_id');

	}

	public function delivery_serie()
	{

		return $this->belongsTo('App\Models\DeliverySerie', 'delivery_serie_id');

	}

  public function scopeNotPaidYet($query)
  {

    return $query->whereNull('paid_at');

  }

  public function scopeOnlyPaid($query)
  {

    return $query->whereNotNull('paid_at');

  }

  public function scope($query)
  {

    return $query->whereNull('paid_at');

  }

	public function isRegionalAddress()
	{

		$region = substr($this->destination_zip, 0, 2);
		
		if ($region === '33') return TRUE;
		else return FALSE;

	}

  /**
   * Scope
   */

  /**
   * Get current unpaid order building
   */
  public function scopeGetCurrent($query)
  {

    return $this->orderBy('created_at', 'desc')->notPaidYet()->first();

  }

}