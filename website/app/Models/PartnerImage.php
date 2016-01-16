<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerImage extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'partner_images';

	/**
	 * Belongs To
	 */
	
	public function partner()
	{

		return $this->belongsTo('App\Models\Partner', 'partner_id');

	}

	/**
	 * Other
	 */

    public function getImageUrl()
    {

    	return url('/public/uploads/' . $this->folder . '/' . $this->filename);

    }

}