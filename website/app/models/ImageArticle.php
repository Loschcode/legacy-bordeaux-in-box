<?php namespace App\Models;

class ImageArticle extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'image_articles';

	/**
	 * Create / Update
	 */
	public static function boot()
    {

        parent::boot();

        static::creating(function($image_article)
        {

        	if (empty($image_article->slug))
        	{

           		$image_article->slug = Str::slug($image_article->title);

       		}

        });

        static::updating(function($image_article)
        {


        });

    }

	/**
	 * Belongs To
	 */
	
	public function user()
	{

		return $this->belongsTo('User', 'user_id');

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

	public function get_next()
	{
		return static::where('id', '>', $this->id)->orderBy('created_at', 'asc')->first();
	}

	public function get_previous()
	{
		return static::where('id', '<', $this->id)->orderBy('created_at', 'desc')->first();
	}

}