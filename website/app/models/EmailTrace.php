<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTrace extends Model {

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'email_traces';

  /**
   * Belongs To
   */
  
  public function customer()
  {

    return $this->belongsTo('App\Models\Customer', 'customer_id');

  }

  
  public function customer_profile()
  {

    return $this->belongsTo('App\Models\CustomerProfile', 'customer_profile_id');

  }


}