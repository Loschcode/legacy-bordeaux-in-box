<?php namespace App\Models;

class ProductFilterBox extends Eloquent {

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'product_filter_boxes';

  protected $fillable = array('partner_product_id', 'box_id');

  /**
   * Belongs To
   */
  
  public function product()
  {

    return $this->belongsTo('PartnerProduct', 'partner_product_id');

  }

  public function box()
  {

    return $this->belongsTo('Box', 'box_id');

  }

  /**
   * Other
   */

}