<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerConnect extends Model {

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'customer_connects';

  /**
   * Belongs To
   */
  
  public function customer()
  {

    return $this->belongsTo('App\Models\Customer', 'customer_id');

  }

  /**
   * Auto login system
   * @param  text $token
   * @return 
   */
  public static function tryToLogin($token) {

    $customer_connect = CustomerConnect::where('token', '=', $token)->first();

    if ($customer_connect !== NULL) {

      $customer = $customer_connect->customer()->first();
      \Auth::guard('customer')->login($customer);

      return TRUE;

    } else {

      return FALSE;
      
    }

  }

  public static function setAndGetToken($customer) {

    $customer_connect = new CustomerConnect;
    $customer_connect->token = \Crypt::encrypt($customer->id.uniqid().time());
    $customer_connect->customer_id = $customer->id;
    $customer_connect->save();

    return $customer_connect->token;

  }

}