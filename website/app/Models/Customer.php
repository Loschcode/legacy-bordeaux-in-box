<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model implements AuthenticatableContract, CanResetPasswordContract 
{

 	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'customers';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	/**
	 * HasOne
	 */
	
	public function order_building()
	{

		return $this->hasOne('App\Models\CustomerOrderBuilding');
		
	}
	
	/**
	 * HasMany
	 */

	public function profiles()
	{

		return $this->hasMany('App\Models\CustomerProfile');
		
	}

	public function notes()
	{

		return $this->hasMany('App\Models\CustomerProfileNote');

	}
	
	public function image_articles()
	{

		return $this->hasMany('App\Models\ImageArticle');
		
	}

	public function blog_articles()
	{

		return $this->hasMany('App\Models\BlogArticle');
		
	}

	public function orders()
	{

		return $this->hasMany('App\Models\Order');
		
	}

	public function payments()
	{

		return $this->hasMany('App\Models\Payment');
		
	}

	/**
	 * Methods
	 */
	public function hasBillingAddress()
	{

		if (($this->address) && ($this->city) && ($this->zip)) return TRUE;
		else return FALSE;

	}

	public function getFullName()
	{

		return ucwords(mb_strtolower($this->first_name . ' ' . $this->last_name));

	}

	public function getTurnover()
	{

		return $this->payments()->where('paid', '=', TRUE)->sum('amount');

	}

	public function getFullAddress()
	{

		return $this->address . ", " . $this->city . " (" . $this->zip . ")";

	}
}
