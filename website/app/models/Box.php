<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Box extends Eloquent {

	use SoftDeletingTrait;

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
	
	public function user_profiles()
	{

		return $this->hasMany('UserProfile');

	}

	public function orders()
	{

		return $this->hasMany('Order');

	}

	public function questions()
	{

		return $this->hasMany('BoxQuestion');

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