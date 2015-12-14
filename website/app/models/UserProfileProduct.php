<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfileProduct extends Model {

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'user_profile_products';

  /**
   * Belongs To
   */
  
  public function user_profile()
  {

    return $this->belongsTo('App\Models\UserProfile', 'user_profile_id');

  }

  public function serie_product()
  {

    return $this->belongsTo('App\Models\SerieProduct', 'serie_product_id');

  }

  public function partner_product()
  {

    return $this->belongsTo('App\Models\PartnerProduct', 'partner_product_id');

  }

  /**
   * Other
   */
  
  public static function getAverageCost($serie_id)
  {

    $serie_products = self::join('serie_products', 'user_profile_products.serie_product_id', '=', 'serie_products.id')
              ->where('serie_products.delivery_serie_id', '=', $serie_id)->get();

    return self::average_calculator($serie_products, 'cost_per_unity');

  }

  public static function getAverageValue($serie_id)
  {

    $serie_products = self::join('serie_products', 'user_profile_products.serie_product_id', '=', 'serie_products.id')
              ->where('serie_products.delivery_serie_id', '=', $serie_id)->get();

    return self::average_calculator($serie_products, 'value_per_unity');

  }

  public static function getAverageWeight($serie_id)
  {

    $serie_products = self::join('serie_products', 'user_profile_products.serie_product_id', '=', 'serie_products.id')
              ->join('partner_products', 'serie_products.partner_product_id', '=', 'partner_products.id')
              ->where('serie_products.delivery_serie_id', '=', $serie_id)->get();

    return self::average_calculator($serie_products, 'weight');

  }


  private static function average_calculator($serie_products, $label) {

    $averages = [];

    foreach ($serie_products as $serie_product) {

      if (!isset($averages[$serie_product->order_id])) $averages[$serie_product->order_id] = [];
      array_push($averages[$serie_product->order_id], $serie_product->$label);

    }

    $final_averages = [];

    foreach ($averages as $average) {

      $final_averages[] = array_sum($average);

    }

    if (count($final_averages) <= 0) return 0;

    return round(array_sum($final_averages) / count($final_averages), 2);


  }

}