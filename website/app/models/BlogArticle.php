<?php namespace App\Models;

class BlogArticle extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'blog_articles';

	/**
	 * Create / Update
	 */
	public static function boot()
    {

        parent::boot();

        static::creating(function($blog_article)
        {

        	if (empty($blog_article->slug))
        	{

           		$blog_article->slug = Str::slug($blog_article->title);

       		}

        });

        static::updating(function($blog_article)
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
		
    public function getThumbnailAttribute($value)
    {

    	$thumbnail = json_decode($value);
    	$thumbnail->full = url('/public/uploads/' . $thumbnail->folder . '/' . $thumbnail->filename);
    	return $thumbnail;

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