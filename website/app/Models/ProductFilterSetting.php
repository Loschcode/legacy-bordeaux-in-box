<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFilterSetting extends Model {

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'product_filter_settings';

  /**
   * Belongs To
   */

  public function delivery_serie()
  {

    return $this->belongsTo('App\Models\DeliverySerie', 'delivery_serie_id');

  }

  /**
   * Other
   */

}