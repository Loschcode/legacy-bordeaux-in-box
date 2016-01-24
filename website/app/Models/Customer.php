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
	protected $hidden = ['password', 'remember_token'];

	/**
 	 * The accessors to append to the model's array form.
   *
   * @var array
   */
 	protected $appends = ['phone_format', 'full_name'];

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
	 * Accessors
	 */
	public function getPhoneFormatAttribute()
	{
		return $this->getPhone();
	}

	public function getFullNameAttribute()
	{
		return $this->getFullName();
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

	/**
	 * Prettify the phone format
	 * @return string
	 */
	public function getPhone()
	{
		$phone = trim($this->phone);

		$formatPhone = str_replace('.', '', $phone);
		$formatPhone = str_replace(' ', '', $formatPhone);
		$formatPhone = str_replace('+330', '0', $formatPhone);
		$formatPhone = str_replace('+33', '0', $formatPhone);

		// Ok it's well formated now, we can split the numbers
		// for a better display. Else we let the phone as is.
		if (strlen($formatPhone) === 10) {
			$formatPhone = join('.', str_split($formatPhone, 2));
			return $formatPhone;
		}

		return $phone;

	}
}
