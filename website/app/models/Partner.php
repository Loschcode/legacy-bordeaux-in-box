<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Partner extends Eloquent {

	use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'partners';

	/**
	 * Create / Update
	 */
	public static function boot()
    {

        parent::boot();

        static::creating(function($partner)
        {

        	if (empty($partner->slug))
        	{

           		$partner->slug = Str::slug($partner->name);

       		}

        });

        static::updating(function($partner)
        {

        	$partner->slug = Str::slug($partner->name);

        });

        static::deleting(function($partner)
        {

        	// We don't forget to delete all the images associated to this entry
        	$images = $partner->images()->get();

        	foreach ($images as $image) {

        		delete_file($image->filename, $image->folder);
        		$image->delete();
        	}


        });

    }

	/**
	 * Belongs To
	 */
	
	public function blog_article()
	{

		return $this->belongsTo('BlogArticle', 'blog_article_id');

	}

	/**
	 * HasMany
	 */
	
	public function products()
	{

		return $this->hasMany('PartnerProduct');

	}
	
	public function images()
	{

		return $this->hasMany('PartnerImage');

	}

	/**
	 * Other
	 */

}