<?php

class PartnerImage extends Eloquent {

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

		return $this->belongsTo('Partner', 'partner_id');

	}

	/**
	 * Other
	 */

    public function getImageUrl()
    {

    	return url('/public/uploads/' . $this->folder . '/' . $this->filename);

    }

}