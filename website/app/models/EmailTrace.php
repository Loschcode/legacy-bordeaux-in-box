<?php

class EmailTrace extends Eloquent {

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

    return $this->belongsTo('User', 'user_id');

  }

  
  public function user_profile()
  {

    return $this->belongsTo('UserProfile', 'user_profile_id');

  }


}