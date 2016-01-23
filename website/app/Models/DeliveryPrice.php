<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryPrice extends Model {

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

	public function getCheckboxFrequencyGiftText()
	{
		return 
			'<span class="labelauty-title">' . $this->title . '</span><br/>' .
			'<span class="labelauty-description">' . $this->readableFrequency() . '</span>';
	}

	public function getCheckboxFrequencySubscriptionText()
	{

		if ($this->frequency == 1) {
			$title = number_format($this->unity_price, 2) . '&euro;';
		} else {
			$title = number_format($this->unity_price, 2) . '&euro; par mois';
		}

		return 
			'<span class="labelauty-title">' . $title . '</span><br/>' .
			'<span class="labelauty-description">' . $this->readableFrequency() . '</span>';
	}

}