<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model {

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'order_payments';


  /**
   * Create / Update
   */
  public static function boot()
  {

    parent::boot();

    static::created(function($order_payment)
    {

      /**
       * If it's a duplicate we don't add it
       */
      if (App\Models\OrderPayment::where('payment_id', '=', $order_payment->payment_id)
                                 ->where('order_id', '=', $order_payment->order_id)
                                 ->first() !== NULL) {

        $order_payment->delete();

      }

    });

    static::updating(function($order_payment)
    {

    });

  }

}