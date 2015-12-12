<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliverySerie extends Model {

	public function manyThroughMany($related, $through, $firstKey, $secondKey, $pivotKey)
	{
		$model = new $related;
		$table = $model->getTable();
		$throughModel = new $through;
		$pivot = $throughModel->getTable();

		return $model
		->join($pivot, $pivot . '.' . $pivotKey, '=', $table . '.' . $secondKey)
		->select($table . '.*')
		->where($pivot . '.' . $firstKey, '=', $this->id);
	}

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'delivery_series';

	/**
	 * HasMany
	 */
	
	public function payments()
	{

		return $this->hasManyThrough('Payment', 'Order');

	}

	public function orders()
	{

		return $this->hasMany('Order');

	}

	public function user_order_buildings()
	{

		return $this->hasMany('UserOrderBuilding');

	}

  public function serie_products()
  {

    return $this->hasMany('SerieProduct');

  }

  public function user_profile_products()
  {

  	return $this->hasManyThrough('UserProfileProduct', 'SerieProduct', 'delivery_serie_id', 'serie_product_id');

  }

  /**
   * HasOne
   */
  public function product_filter_setting()
  {

    return $this->hasOne('ProductFilterSetting');

  }

	/**
	 * Other
	 */

	public static function nextOpenSeries()
	{

		return self::where('delivery', '>', date('Y-m-d', time()))->whereNull('closed')->orderBy('delivery', 'asc')->get();

	}

	public static function getTotalPaid()
	{

		return self::join('orders', 'orders.delivery_serie_id', '=', 'delivery_series.id')->sum('orders.already_paid');

	}

	public function wasDelivered()
	{

		if ($this->orders()->whereNotNull('date_sent')->count() > 0) {
			return true;
		} else {
			return false;
		}

	}

	public function serieProductsAreReady()
	{

		$ready_serie_products = $this->serie_products()->where('ready', '=', TRUE)->count();
		$total_serie_products = $this->serie_products()->count();

		if ($ready_serie_products === $total_serie_products) return TRUE;
		else return FALSE;

	}

	public function isUnlockable()
	{

		if ($this->orders()->DeliveredOrders()->count() > 0)

			return FALSE;

		else

			return TRUE;

	}

	public function getFormStats()
	{

		$form_stats = [];

  	$answers = $this->orders()
  	->join('user_profiles', 'orders.user_profile_id', '=', 'user_profiles.id')
  	->join('user_answers', 'user_profiles.id', '=', 'user_answers.user_profile_id')
  	->select('user_answers.*')
  	->groupBy('user_answers.id')->get();

		foreach ($answers as $user_answer) {

				$box_question_id = $user_answer->box_question_id;
				$box_question = BoxQuestion::find($box_question_id);

				if ($box_question !== NULL) {

					$box_id = $box_question->box_id;
					$real_answer = $user_answer->answer;

					if ($box_question->type === 'date') {

						 // We convert the date to a category of people
	          $real_answer = convert_date_to_age($real_answer) . ' ans';

					} elseif ($box_question->type == 'children_details') {

						// Nothing yet

					}

					if (!isset($form_stats[$box_id][$box_question_id][$real_answer])) $form_stats[$box_id][$box_question_id][$real_answer] = 1;
					else $form_stats[$box_id][$box_question_id][$real_answer]++;

				}

			}

		return $form_stats;

	}

	public function getCounter()
	{

		if (!$this->goal) return FALSE;
		
		$current_orders_count = $this->orders()->notCanceledOrders()->count();
		return $this->goal - $current_orders_count;

	}

	public function getAchievement()
	{

		if (!$this->goal) return FALSE;

		$current_orders_count = $this->orders()->notCanceledOrders()->count();
		if (($current_orders_count === 0) || ($this->getCounter() === FALSE)) return $current_orders_count;

		return round(($current_orders_count / $this->goal) * 100, 2);

	}

}