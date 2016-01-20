<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model {

  use SoftDeletes;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'payments';

	/**
	 * Belongs To
	 */
	
	public function customer()
	{

		return $this->belongsTo('App\Models\Customer', 'customer_id');

	}

	public function order()
	{

		return $this->belongsTo('App\Models\Order', 'order_id');

	}
	
	public function profile()
	{

		return $this->belongsTo('App\Models\CustomerProfile', 'customer_profile_id');

	}

  /**
   * HasOne
   */

  public function company_billing_lines()
  {

    return $this->hasOne('App\Models\CompanyBillingLine');

  }

  public function company_billing()
  {

    $middle = $this->hasOne('App\Models\CompanyBillingLine'); 
    return $middle->getResults()->belongsTo('App\Models\CompanyBilling', 'company_billing_id'); 

  }

	/**
	 * Other
	 */
	
	public static function getTotal()
	{
		return self::where('paid', '=', true)->sum('amount');

	}

  public function getBillEncryptedAccess()
  {

    return $this->company_billing()->first()->encrypted_access;

  }
	
}