<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Database\Eloquent\Model;

class UserProfileNote extends Model {

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

		return $this->belongsTo('App\Models\User', 'user_id');

	}

	public function user_profile()
	{

		return $this->belongsTo('App\Models\UserProfile', 'user_profile_id');

	}

}