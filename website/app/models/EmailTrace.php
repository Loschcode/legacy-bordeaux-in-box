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
  
  public function user()
  {

    return $this->belongsTo('App\Models\User', 'user_id');

  }

  
  public function user_profile()
  {

    return $this->belongsTo('App\Models\UserProfile', 'user_profile_id');

  }


}