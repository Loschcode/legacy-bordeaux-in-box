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
   * Create / Update
   */
  public static function boot()
  {

    parent::boot();

    static::creating(function($payment)
    {

    });

    static::updating(function($payment)
    {

    });

    static::deleting(function($payment) {


    });

  }

	/**
	 * Belongs To
	 */
	
	public function customer()
	{

		return $this->belongsTo('App\Models\Customer', 'customer_id');

	}

	public function profile()
	{

		return $this->belongsTo('App\Models\CustomerProfile', 'customer_profile_id');

	}

  /**
   * HasManyThrough
   */
  public function orders()
  {

    return $this->belongsToMany('App\Models\Order', 'order_payments');
    //return $this->hasManyThrough('App\Models\Order', 'App\Models\OrderPayment');

  }

  public function ScopeWithOrders($query)
  {

    $query->join('order_payments', 'order_payments.payment_id', '=', 'payments.id');
    

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