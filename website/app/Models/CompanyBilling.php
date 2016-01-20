<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyBilling extends Model {

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'company_billings';

  /**
   * Create / Update
   */
  public static function boot()
    {

        parent::boot();

        static::creating(function($billing)
        {


        });

        static::updating(function($billing)
        {

        });

    }

  /**
   * Belongs To
   */

  /**
   * Has Many
   */
  public function billing_lines()
  {

    return $this->hasMany('\App\Models\CompanyBillingLine');

  }

  public function order()
  {

    return $this->hasOne('\App\Models\Order');

  }

  /**
   * Accessors
   */


}