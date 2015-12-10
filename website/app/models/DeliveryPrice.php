<?php

class DeliveryPrice extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'delivery_prices';

	public function readableFrequency()
	{

		if ($this->frequency == 1) return 'Une seule livraison';
		elseif ($this->frequency == 0) return 'Sans engagement';

		else return 'pendant ' . $this->frequency . ' mois';

	}

}