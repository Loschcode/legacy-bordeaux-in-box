<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CustomerProfileNote extends Model {

	use SoftDeletes;

    protected $dates = ['deleted_at'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'customer_profile_notes';

	/**
	 * Belongs To
	 */
	
	public function administrator()
	{

		return $this->belongsTo('App\Models\Administrator', 'administrator_id');

	}

	public function customer_profile()
	{

		return $this->belongsTo('App\Models\CustomerProfile', 'customer_profile_id');

	}

}