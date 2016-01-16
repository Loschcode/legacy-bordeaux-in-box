<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFilterBox extends Model {

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

    return $this->belongsTo('App\Models\PartnerProduct', 'partner_product_id');

  }

  public function box()
  {

    return $this->belongsTo('App\Models\Box', 'box_id');

  }

  /**
   * Other
   */

}