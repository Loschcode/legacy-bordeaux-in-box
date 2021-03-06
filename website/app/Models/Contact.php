<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model {

	protected $appends = ['clean_message'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'contacts';

	public function getCleanMessageAttribute()
	{
		$this->attributes['clean_message'] = htmlentities(nl2br($this->message));

		return $this->attributes['clean_message'];

	}

}