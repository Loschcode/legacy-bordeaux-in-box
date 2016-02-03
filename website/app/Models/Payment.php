<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'payments';

  protected $casts = [
      
      'paid' => 'boolean',
  ];

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

    $query->join('order_payments', 'order_payments.payment_id', '=', 'payments.id')
          ->select('payments.*');

    

  }

  public function ScopeWithoutOrders($query)
  {

    return $query->leftJoin('order_payments', 'order_payments.payment_id', '=', 'payments.id')
                 ->whereNull('order_payments.order_id')
                 ->select('payments.*');

  }
  
  /**
   * HasOne
   */

  public function company_billing_lines()
  {

    return $this->hasMany('App\Models\CompanyBillingLine');

  }

  public function getCompanybillings()
  {


    return $this->where('payments.id', '=', $this->id)
                ->join('company_billing_lines', 'company_billing_lines.payment_id', '=', 'payments.id')
                ->join('company_billings', 'company_billing_lines.company_billing_id', '=', 'company_billings.id')
                ->groupBy('company_billings.id')
                ->select('company_billings.*')->get();



  }

	/**
	 * Other
	 */
	
	public static function getTotal()
	{
		return self::where('paid', '=', true)->sum('amount');

	}

  /*public function getBillEncryptedAccess()
  {

    return $this->company_billings()->first()->encrypted_access;

  }*/
	
}