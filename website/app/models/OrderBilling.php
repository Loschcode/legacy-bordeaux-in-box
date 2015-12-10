<?php namespace App\Models;

class OrderBilling extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'order_billings';

	/**
	 * Belongs To
	 */
	
	public function order()
	{

		return $this->belongsTo('Order', 'order_id');

	}

	public function emailReadableBilling()
	{

		return $this->address . ', '. $this->city . ' (' . $this->zip . ')';

	}

}