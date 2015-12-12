<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderBilling extends Model {

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