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
   * Attributes
   */
  
  public function getDestinationAddressAttribute() {

    if ($this->destination_coordinate()->first() === NULL)
      return '';
    
    return $this->destination_coordinate()->first()->address;

  }

  public function getDestinationAddressDetailAttribute() {

    if ($this->destination_coordinate()->first() === NULL)
      return '';
    
    return $this->destination_coordinate()->first()->address_detail;

  }

  public function getDestinationCityAttribute() {

    if ($this->destination_coordinate()->first() === NULL)
      return '';

    return $this->destination_coordinate()->first()->city;

  }

  public function getDestinationZipAttribute() {

    if ($this->destination_coordinate()->first() === NULL)
      return '';

    return $this->destination_coordinate()->first()->zip;

  }

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

  
  public function destination_coordinate()
  {

    return $this->belongsTo('App\Models\Coordinate', 'destination_coordinate_id');

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

    // TO CHANGE AT SOME POINT
		$region = substr($this->destination_zip, 0, 2);
		
		if ($region === '33') return TRUE;
		else return FALSE;

	}

  public function getFullDestinationAddress()
  {

    return $this->destination_address . ', ' . $this->destination_city . ' ' . $this->destination_zip;

  }

  /**
   * Scope
   */

  /**
   * Get current unpaid order building
   */
  public function scopeGetCurrent($query)
  {

    return $query->orderBy('created_at', 'desc')->notPaidYet();

  }

  /**
   * Get last paid order building
   */
  public function scopeGetLastPaid($query)
  {

    return $query->orderBy('created_at', 'desc')->onlyPaid();

  }

}