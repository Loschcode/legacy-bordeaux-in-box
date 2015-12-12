<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOrderBuilding extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_order_buildings';

	/**
	 * Belongs To
	 */
	
	public function user()
	{

		return $this->belongsTo('User', 'user_id');

	}

	public function order_preference()
	{

		return $this->belongsTo('UserOrderPreference', 'user_order_preference_id');

	}

	public function profile()
	{

		return $this->belongsTo('UserProfile', 'user_profile_id');

	}

	public function delivery_serie()
	{

		return $this->belongsTo('DeliverySerie', 'delivery_serie_id');

	}

	public function isRegionalAddress()
	{

		$region = substr($this->destination_zip, 0, 2);
		
		if ($region === '33') return TRUE;
		else return FALSE;

	}

}