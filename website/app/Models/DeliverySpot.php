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

  public function getAddressAttribute() { return $this->coordinate()->first()->address; }
  public function getCityAttribute() { return $this->coordinate()->first()->city; }
  public function getZipAttribute() { return $this->coordinate()->first()->zip; }
  
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
	 * Methods
	 */
	
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

  /**
   * Fetch the google map url of the spot
   * @return string
   */
  public function getUrlGoogleMap()
  {

    $address = str_slug($this->address . ' ' . $this->city . ' ' . $this->zip, "+");

    return 'https://www.google.com/maps/place/' . $address;

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