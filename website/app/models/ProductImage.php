<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model {

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'product_images';

  /**
   * Belongs To
   */
  
  public function product()
  {

    return $this->belongsTo('PartnerProduct', 'partner_product_id');

  }

  /**
   * Other
   */

    public function getImageUrl()
    {

      return url('/public/uploads/' . $this->folder . '/' . $this->filename);

    }

}