<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDestination extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'order_destinations';
  
  /*public function getAddressAttribute() {

    if ($this->coordinate()->first() === NULL)
      return '';

    return $this->coordinate()->first()->address; 

  }
  public function getCityAttribute() {

    if ($this->coordinate()->first() === NULL)
      return '';

    return $this->coordinate()->first()->city;

  }
  public function getZipAttribute() {

    if ($this->coordinate()->first() === NULL)
      return '';

    return $this->coordinate()->first()->zip;

  }*/

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

	public function emailReadableDestination()
	{

		return $this->address . ', '. $this->city . ' (' . $this->zip . ')';

	}

}