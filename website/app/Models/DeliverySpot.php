<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DeliverySpot extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'delivery_spots';

  protected $casts = [
      
      'active' => 'boolean',
  ];

  public function getAddressAttribute() {

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

  }
  
	/**
	 * Create / Update
	 */
	public static function boot()
    {

        parent::boot();

        static::creating(function($spot)
        {

        	if (empty($spot->slug))
        	{

           		$spot->slug = Str::slug($spot->name);

       		}

        });

        static::updating(function($spot)
        {

        	$spot->slug = Str::slug($spot->name);

        });

    }

  /**
   * Belongs To
   */
  
  public function coordinate()
  {

    return $this->belongsTo('App\Models\Coordinate', 'coordinate_id');

  }

	/**
	 * HasMany
	 */
  
	public function orders()
	{

		return $this->hasMany('App\Models\Order');

	}

  /**
   * Scope
   */
  
  public function scopeOrderByDistanceFrom($query, $coordinate) {

    $latitude = $coordinate->latitude;
    $longitude = $coordinate->longitude;
    
    return $query->join('coordinates', 'coordinates.id', '=', 'delivery_spots.coordinate_id')->orderByRaw("POW((coordinates.longitude-$longitude),2) + POW((coordinates.latitude-$latitude),2)");

  }

	/**
	 * Methods
	 */
  
  public function getDistanceFromCustomer($customer) {

    $customer_coordinate = $customer->coordinate()->first();
    return $this->coordinate()->first()->getDistanceFrom($customer_coordinate);

  }
	
  public function getDistanceFromCoordinate($coordinate) {

    return $this->coordinate()->first()->getDistanceFrom($coordinate);

  }

	/**
	 * Get the orders linked with this spot for a specific series
	 * @param  object $series
	 * @return object      
	 */
	public function getSeriesOrders($series)
	{

		return $this->orders()->where('take_away', '=', true)->where('delivery_serie_id', '=', $series->id)->notCanceledOrders();

	}

	/**
	 * Get the orders linked with this spot for a specific series
	 * @param  object $series
	 * @return object      
	 */
	public function getDeliveredSeriesOrders($series)
	{

		return $this->getSeriesOrders($series)->where('status', '=', 'delivered');

	}

  public function getFullAddress()
  {

    return $this->address . ', ' . $this->city . ' ' . $this->zip;

  }

  public function readableSpot()
  {

    return $this->name . ' <br/> <small><i class="fa fa-map-marker"></i>' . $this->address . ', ' . $this->city . ' ('.$this->zip.')</span>';

  }

	public function emailReadableSpot()
	{

		return $this->name . ' (' . $this->address . ', '. $this->city . ' ' . $this->zip . ' )';

	}
	
}