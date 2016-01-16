<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDestination extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'order_destinations';

	/**
	 * Belongs To
	 */
	
	public function order()
	{

		return $this->belongsTo('App\Models\Order', 'order_id');

	}

	public function emailReadableDestination()
	{

		return $this->address . ', '. $this->city . ' (' . $this->zip . ')';

	}

}