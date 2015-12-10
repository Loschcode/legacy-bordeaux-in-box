<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Contact extends Eloquent {

	use SoftDeletingTrait;

    protected $dates = ['deleted_at'];
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