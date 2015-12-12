<?php namespace App\Models;

class ProductFilterBoxAnswer extends Model {

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'product_filter_box_answers';

  protected $fillable = ['partner_product_id', 'box_question_id', 'to_referent_slug', 'answer', 'slug'];

  /**
   * Create / Update
   */
  public static function boot()
  {

    parent::boot();

    static::creating(function($product_filter_box_answers)
    {

      if (empty($product_filter_box_answers->slug))
      {

        $product_filter_box_answers->slug = Str::slug($product_filter_box_answers->answer);

      }

    });

    static::updating(function($product_filter_box_answers)
    {

      if (empty($product_filter_box_answers->slug))
      {

        $product_filter_box_answers->slug = Str::slug($product_filter_box_answers->answer);

      }

    });

  }

  /**
   * Belongs To
   */
  
  public function product()
  {

    return $this->belongsTo('PartnerProduct', 'partner_product_id');

  }

  public function box_question()
  {

    return $this->belongsTo('BoxQuestion', 'box_question_id');

  }

  /**
   * Other
   */

}