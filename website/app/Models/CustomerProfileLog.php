<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerProfileLog extends Model {


  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'customer_profile_logs';

  /**
   * Belongs To
   */
  
  public function administrator()
  {

    return $this->belongsTo('App\Models\Administrator', 'administrator_id');

  }

  public function customer_profile()
  {

    return $this->hasMany('App\Models\CustomerProfile', 'customer_profile_id');

  }

  /**
   * Accessors
   */
    
  public function getMetadataAttribute($value)
  {

    if (empty($value))
      return [];

    $metadata = json_decode($value);
    return $metadata;

  }

  public function setMetadataAttribute($value)
  {
    
    $this->attributes['metadata'] = json_encode($value);

  }

}