<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SerieProduct extends Model {

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'serie_products';

  /**
   * Belongs To
   */
  
  public function product()
  {

    return $this->belongsTo('PartnerProduct', 'partner_product_id');

  }

  public function delivery_serie()
  {

    return $this->belongsTo('DeliverySerie', 'delivery_serie_id');

  }

  /**
   * HasMany
   */
  
  public function user_profile_products()
  {

    return $this->hasMany('UserProfileProduct');

  }

  /**
   * Other
   */
  
  public static function scopeSelectOnlyIds($query) {

    return $query->select('serie_products.id');

  }
  
  /**
   * We will avoid the only regional products
   */
  public static function scopeNotRegional($query) {

    return $query->where('partner_products.regional_only', '=', FALSE);

  }

  /**
   * We target a peculiar box
   */
  public static function scopeOnlyBox($query, $box) {

    return $query
    ->join('product_filter_boxes', 'product_filter_boxes.partner_product_id', '=', 'partner_products.id')
    ->where('product_filter_boxes.box_id', '=', $box->id);

  }

  /**
   * We joins the partner products, just to process the query
   */
  public static function scopeJoinProducts($query) {

    return $query
    ->join('partner_products', 'serie_products.partner_product_id', '=', 'partner_products.id')
    ->select('serie_products.*'); // At the end we only want the serie_products datas

  }

  /**
   * We target the ready orders
   */
  public static function scopeIsReady($query) {

    return $query->where('serie_products.ready', '=', TRUE);

  }

  /**
   * We target the birthday ready orders
   */
  public static function scopeIsBirthdayReady($query) {

    return $query->where('partner_products.birthday_ready', '=', TRUE);

  }


  /**
   * We target the sponsor ready orders
   */
  public static function scopeIsSponsorReady($query) {

    return $query->where('partner_products.sponsor_ready', '=', TRUE);

  }

  /**
   * 
   */
  public static function scopeResetQuantityLeft($query) {

     return DB::statement(DB::raw('

        UPDATE serie_products
        SET quantity_left = quantity

      '));
     
  }
  
  /**
   * Will get the data from the field or try to find from the previous similar serie product
   * Which means it got a similar product
   * @param  string $data e.g. `cost_per_unity`
   * @return mixed
   */
  public function getDataOrPrevious($data)
  {

    if (($this->$data) && ($this->$data >= 0)) return $this->$data;
    else {

      $previous_serie_product = SerieProduct::where('partner_product_id', '=', $this->partner_product_id)->where('id', '!=', $this->id)->orderBy('created_at', 'desc')->first();

      if ($previous_serie_product !== NULL) return $previous_serie_product->$data;
      else return NULL;

    }

  }

}