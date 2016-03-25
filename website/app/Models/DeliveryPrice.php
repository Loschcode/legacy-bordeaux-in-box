<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryPrice extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'delivery_prices';

  protected $casts = [
      
      'gift' => 'boolean',
  ];

	/**
	 * Guess if we need to focus that offer
	 * - For a gift, we focus the middle offer
	 * @return string/void The css class to focus
	 */
	public function getLabelautyFocusClass()
	{

		// Fetch delivery prices ordered by price low to high
		$delivery_prices = DeliveryPrice::where('gift', $this->gift)->orderBy('unity_price', 'asc')->get();

		// Gift case
		if ($this->gift) {

			// What's the middle offer ?
			$id_middle_offer = round($delivery_prices->count() / 2, 0, PHP_ROUND_HALF_DOWN);

			// Check if the current offer is the "middle" offer
			if ($delivery_prices[$id_middle_offer]->id == $this->id) {

				// We focus it.
				return 'labelauty-choose-frequency-focus-big';
			}

			return 'labelauty-choose-frequency-focus-small';
			// End
		
		} else {

			// We check if the lowest offer
			if ($delivery_prices[0]->id == $this->id) {
				return 'labelauty-choose-frequency-focus-big';
			}

			return 'labelauty-choose-frequency-focus-small';
			// End
		
		}

	}

	public function readableFrequency()
	{

		if ($this->frequency == 1) return 'Une seule livraison';
		elseif ($this->frequency == 0) return 'Sans engagement (apres 2 mois)';

		else return 'pendant ' . $this->frequency . ' mois';

	}

	public function getCheckboxFrequencyGiftText()
	{		

		return 
			'<span class="labelauty-title">' . $this->title . '</span><br/>' .
			'<span class="labelauty-description">' . strtoupper($this->readableFrequency()) . ' (' . number_format($this->unity_price, 2) . '&euro;)</span>';
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
			'<span class="labelauty-description">' . strtoupper($this->readableFrequency()) . '</span>';
	}

}