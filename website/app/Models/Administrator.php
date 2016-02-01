<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Html;

class Administrator extends Model implements AuthenticatableContract 
{

  use Authenticatable;

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'administrators';

  /**
   * The attributes excluded from the model's JSON form.
   *
   * @var array
   */
  protected $hidden = ['password', 'remember_token'];

  public function getFullName()
  {

    return ucwords(mb_strtolower($this->first_name . ' ' . $this->last_name));

  }

  /**
   * HasMany
   */
  public function notes()
  {

    return $this->hasMany('App\Models\CustomerProfileNote');

  }

}
