<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Html;

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
 	protected $appends = ['address', 'city', 'zip', 'phone_format', 'role_format', 'turnover', 'full_name'];
  
  public function getAddressAttribute() {

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

  }

  /**
   * Create / Update
   */
  public static function boot() {

        parent::boot();

        static::creating(function($customer) {

        });

        static::updating(function($customer) {

        });

        static::deleting(function($customer) {

        });

  }

	/**
	 * HasOne
	 */

  /**
   * BelongsTo
   */

  public function coordinate()
  {

    return $this->belongsTo('App\Models\Coordinate', 'coordinate_id');

  }
	
	/**
	 * HasMany
	 */

  public function order_buildings()
  {

    return $this->hasMany('App\Models\CustomerOrderBuilding');
    
  }

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
		return readable_customer_phone($this->phone);
	}

	public function getRoleFormatAttribute()
	{
		return readable_customer_role($this->role);
	}

	public function getFullNameAttribute()
	{
		return $this->getFullName();
	}

	public function getTurnoverAttribute()
	{
		return $this->getTurnover();
	}

  /**
   * Scope
   */
  
  public function scopeWithCoordinateOnly($query)
  {

    return $query->join('coordinates', 'customers.coordinate_id', '=', 'coordinates.id')
          ->select('customers.*');

  }

  public function scopeResearch($query, $search)
  {

    $search_words = explode(' ', $search);

    $query->with('profiles');

    /**
     * If it's an ID
     */

    if (intVal($search) !== 0)
      return $query->where('id', $search);

    foreach ($search_words as $word) {

      $query->where(function ($query) use ($word) {

        $query->orWhere('first_name', 'like', '%' . $word . '%')
        ->orWhere('last_name', 'like', '%' . $word . '%')
        ->orWhere('email', 'like', '%' . $word . '%')
        ->orWhere('phone', 'like', '%' . $word . '%');

      });
    }

    return $query;

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
