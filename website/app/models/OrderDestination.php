<?php

class OrderDestination extends Eloquent {

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

		return $this->belongsTo('Order', 'order_id');

	}

	public function emailReadableDestination()
	{

		return $this->address . ', '. $this->city . ' (' . $this->zip . ')';

	}

}