<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Str;

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

        static::updated(function($blog_article)
        {

          $old_image = $blog_article['original']['thumbnail'];
          $new_image = $blog_article['attributes']['thumbnail'];

          if ($old_image !== $new_image) {

            $image = json_decode($old_image);
            delete_file($image->folder, $image->filename);

          }

        });

        static::deleting(function($blog_article)
        {

          $image = $blog_article->thumbnail;
          delete_file($image->folder, $image->filename);

        });
    }

	/**
	 * Belongs To
	 */
	
	public function administrator()
	{

		return $this->belongsTo('\App\Models\Administrator', 'administrator_id');

	}


	/**
	 * Accessors
	 */
		
    public function getThumbnailAttribute($value)
    {

    	$thumbnail = json_decode($value);
    	$thumbnail->full = url('uploads/' . $thumbnail->folder . '/' . $thumbnail->filename);
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