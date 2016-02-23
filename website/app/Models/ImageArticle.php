<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Str;

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

        static::updated(function($image_article)
        {

          $old_image = $image_article['original']['image'];
          $new_image = $image_article['attributes']['image'];

          if ($old_image !== $new_image) {

            $image = json_decode($old_image);
            delete_file($image->folder, $image->filename);

          }

        });

        static::deleting(function($image_article)
        {

          $image = $image_article->image;
          delete_file($image->folder, $image->filename);

        });

    }

	/**
	 * Belongs To
	 */
	
	public function administrator()
	{

		return $this->belongsTo('App\Models\Administrator', 'administrator_id');

	}

	/**
	 * Accessors
	 */
		
  public function getImageAttribute($value)
  {
    $image = json_decode($value);
    $image->full = '/uploads/' . $image->folder . '/' . $image->filename;
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