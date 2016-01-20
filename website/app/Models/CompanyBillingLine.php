<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyBillingLine extends Model {

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'company_billing_lines';

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
  
  public function billing()
  {

    return $this->belongsTo('\App\Models\Billing', 'billing_id');

  }

  /**
   * Accessors
   */


}