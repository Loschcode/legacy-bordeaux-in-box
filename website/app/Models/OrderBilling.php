<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderBilling extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'order_billings';
  
  public function getAddressAttribute() { return $this->coordinate()->first()->address; }
  public function getCityAttribute() { return $this->coordinate()->first()->city; }
  public function getZipAttribute() { return $this->coordinate()->first()->zip; }

	/**
	 * Belongs To
	 */
	
  public function coordinate()
  {

    return $this->belongsTo('App\Models\Coordinate', 'coordinate_id');

  }
  

	public function order()
	{

		return $this->belongsTo('App\Models\Order', 'order_id');

	}

	public function emailReadableBilling()
	{

		return $this->address . ', '. $this->city . ' (' . $this->zip . ')';

	}

}