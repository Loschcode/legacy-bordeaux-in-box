<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Customer;

class DeliverySerie extends Model {

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

		return $this->hasManyThrough('App\Models\Payment', 'App\Models\Order');

	}

	public function orders()
	{

		return $this->hasMany('App\Models\Order');

	}

  public function customer_profiles_with_orders($fresh=FALSE)
  {

    if (!$fresh) {

      return $this->orders()->notCanceledOrders()
      ->join('customer_profiles', 'orders.customer_profile_id', '=', 'customer_profiles.id')
      ->select('customer_profiles.*');

    } else {

      return $this->orders()->notCanceledOrders()
      ->join('customer_profiles', 'orders.customer_profile_id', '=', 'customer_profiles.id')
      ->where('customer_profiles.created_at', '>', $this->getPreviousSeries()->closed)
      ->select('customer_profiles.*');

    }

  }

  public function customers_payment_profiles($fresh=FALSE)
  {

    if (!$fresh) {

      return $this->orders()->notCanceledOrders()
      ->join('customer_payment_profiles', 'customer_payment_profiles.customer_profile_id', '=', 'orders.customer_profile_id')
      ->select('customer_payment_profiles.*');

    } else {

      return $this->orders()->notCanceledOrders()
      ->join('customer_payment_profiles', 'customer_payment_profiles.customer_profile_id', '=', 'orders.customer_profile_id')
      ->where('customer_payment_profiles.created_at', '>', $this->getPreviousSeries()->closed)
      ->select('customer_payment_profiles.*');

    }

  }

  public function customers_with_orders($fresh=FALSE)
  {

    if (!$fresh) {

      return $this->orders()->notCanceledOrders()
      ->join('customers', 'orders.customer_id', '=', 'customers.id')
      ->select('customers.*');

    } else {

      return $this->orders()->notCanceledOrders()
      ->join('customers', 'orders.customer_id', '=', 'customers.id')
      ->where('customers.created_at', '>', $this->getPreviousSeries()->closed)
      ->select('customers.*');

    }


  }

  public function fresh_customers()
  {
    
    $customers = Customer::where('created_at', '>', $this->getPreviousSeries()->closed);

    if ($this->closed)
      $customers->where('created_at', '<', $this->closed);

    return $customers;

  }

  public function fresh_customer_profiles()
  {
    
    $customer_profiles = CustomerProfile::where('created_at', '>', $this->getPreviousSeries()->closed);

    if ($this->closed)
      $customer_profiles->where('created_at', '<', $this->closed);

    return $customer_profiles;

  }


	public function customer_order_buildings()
	{

		return $this->hasMany('App\Models\CustomerOrderBuilding');

	}

  public function notes()
  {

    return $this->hasMany('App\Models\CustomerProfileNote');

  }

  public function serie_products()
  {

    return $this->hasMany('App\Models\SerieProduct');

  }

  /**
   * HasOne
   */
  public function product_filter_setting()
  {

    return $this->hasOne('App\Models\ProductFilterSetting');

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

  public function scopeWithOrdersOnly($query)
  {

    return $query->join('orders', 'delivery_series.id', '=', 'orders.delivery_serie_id')
                 ->select('delivery_series.*')
                 ->groupBy('delivery_series.id');

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

  	$answers = $this->orders()->notCanceledOrders()
  	->join('customer_profiles', 'orders.customer_profile_id', '=', 'customer_profiles.id')
  	->join('box_question_customer_answers', 'customer_profiles.id', '=', 'box_question_customer_answers.customer_profile_id')
  	->select('box_question_customer_answers.*')
  	->groupBy('box_question_customer_answers.id')->get();

		foreach ($answers as $customer_answer) {

				$box_question_id = $customer_answer->box_question_id;
				$box_question = BoxQuestion::find($box_question_id);

				if ($box_question !== NULL) {

					$real_answer = $customer_answer->answer;

					if ($box_question->type === 'date') {

						// Hot fix: Sometimes the format of date is incorrect
						if (strlen($real_answer) === 10)
	          	$real_answer = get_age($real_answer) . ' ans';
						else 
							$real_answer = 'N/A';

					}

					if (!isset($form_stats[$box_question_id][$real_answer]))
            $form_stats[$box_question_id][$real_answer] = 1;
					else
            $form_stats[$box_question_id][$real_answer]++;

				}

			}

		return $form_stats;

	}

  public function getPreviousSeries()
  {

    return DeliverySerie::where('id', '<', $this->id)->orderBy('id', 'desc')->first();

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