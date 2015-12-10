<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class UserProfileNote extends Eloquent {

	use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_profile_notes';

	/**
	 * Belongs To
	 */
	
	public function user()
	{

		return $this->belongsTo('User', 'user_id');

	}

	public function user_profile()
	{

		return $this->belongsTo('UserProfile', 'user_profile_id');

	}

}