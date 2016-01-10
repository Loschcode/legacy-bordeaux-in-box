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
	
	public function user()
	{

		return $this->belongsTo('App\Models\Customer', 'user_id');

	}

	public function order_preference()
	{

		return $this->belongsTo('App\Models\CustomerOrderPreference', 'user_order_preference_id');

	}

	public function profile()
	{

		return $this->belongsTo('App\Models\CustomerProfile', 'customer_profile_id');

	}

	public function delivery_serie()
	{

		return $this->belongsTo('App\Models\DeliverySerie', 'delivery_serie_id');

	}

	public function isRegionalAddress()
	{

		$region = substr($this->destination_zip, 0, 2);
		
		if ($region === '33') return TRUE;
		else return FALSE;

	}

}