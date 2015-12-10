<?php

class ProductFilterSetting extends Eloquent {

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

    return $this->belongsTo('DeliverySerie', 'delivery_serie_id');

  }

  /**
   * Other
   */

}