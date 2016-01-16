<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Str;

class Box extends Model {

  use SoftDeletes;

    protected $dates = ['deleted_at'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'boxes';

	/**
	 * Create / Update
	 */
	public static function boot()
    {

        parent::boot();

        static::creating(function($box)
        {

        	if (empty($box->slug))
        	{

           		$box->slug = Str::slug($box->title);

       		}

        });

        static::updating(function($box)
        {


        	$box->slug = Str::slug($box->title);


        });

    }

	/**
	 * HasMany
	 */
	
	public function customer_profiles()
	{

		return $this->hasMany('\App\Models\CustomerProfile');

	}

	public function orders()
	{

		return $this->hasMany('App\Models\Order');

	}

	public function questions()
	{

		return $this->hasMany('App\Models\BoxQuestion');

	}

  public function partner_products()
  {

    return $this->hasManyThrough('Product', 'ProductFilterBox');

  }

	/**
	 * Accessors
	 */
		
    public function getImageAttribute($value)
    {

    	$image = json_decode($value);
    	$image->full = '/public/uploads/' . $image->folder . '/' . $image->filename;
    	return $image;

    }

}