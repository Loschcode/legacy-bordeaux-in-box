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
   * Attributes
   */
  
  /*public function getAddressAttribute() {

    if ($this->coordinate()->first() === NULL)
      return '';

    return $this->coordinate()->first()->address; 

  }
  public function getCityAttribute() {

    if ($this->coordinate()->first() === NULL)
      return '';

    return $this->coordinate()->first()->city;

  }
  public function getZipAttribute() {

    if ($this->coordinate()->first() === NULL)
      return '';

    return $this->coordinate()->first()->zip;

  }*/

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

        static::deleting(function($billing) {

          /**
           * We also delete the lines associated
           */
          $company_billing_lines = $billing->billing_lines()->get();
          
          foreach ($company_billing_lines as $company_billing_line) {
            $company_billing_line->delete();
          }

        });

    }

  /**
   * Belongs To
   */

  public function coordinate()
  {

    return $this->belongsTo('App\Models\Coordinate', 'coordinate_id');

  }
  
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