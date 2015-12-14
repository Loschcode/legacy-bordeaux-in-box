<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Payment extends Model {

	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'payments';

	/**
	 * Belongs To
	 */
	
	public function user()
	{

		return $this->belongsTo('App\Models\User', 'user_id');

	}

	public function order()
	{

		return $this->belongsTo('App\Models\Order', 'order_id');

	}
	
	public function profile()
	{

		return $this->belongsTo('App\Models\UserProfile', 'user_profile_id');

	}

	/**
	 * Other
	 */
	
	public static function getTotal()
	{
		return self::where('paid', '=', true)->sum('amount');

	}
	
}