<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartnerProduct extends Model {

  use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'partner_products';

  /**
   * Create / Update
   */
  public static function boot()
    {

        parent::boot();

        static::creating(function($product)
        {

          if (empty($product->slug))
          {

              $product->slug = Str::slug($product->name);

          }

          // We get the master product
          if ($product->master_partner_product_id != $product->id)
          {

            $master_product = PartnerProduct::find($product->master_partner_product_id);
            $product->master_partner_product_id = $master_product->getOriginalMaster();

          }

        });

        static::updating(function($product)
        {

          $product->slug = Str::slug($product->name);

          // We get the master product
          if ($product->master_partner_product_id != $product->id)
          {
            
            $master_product = PartnerProduct::find($product->master_partner_product_id);
            $product->master_partner_product_id = $master_product->getOriginalMaster();

          }

        });

        static::deleting(function($product)
        {

          // We find another master if he has children
          if ($product->master_product_childrens()->first() !== NULL) {

            $new_master_product = $product->master_product_childrens()->first();

            foreach ($product->master_product_childrens()->where('id', '!=', $new_master_product->id)->get() as $master_product_children) {

              $master_product_children->master_partner_product_id = $new_master_product->id;

            }

          }

          // We also delete the advanced filter that's linked to it
          $serie_products = SerieProduct::where('partner_product_id', '=', $product->id)->get();
          foreach ($serie_products as $serie_product) {
            $serie_product->delete();
          }
          $product_filter_box_answers = ProductFilterBoxAnswer::where('partner_product_id', '=', $product->id)->get();
          foreach ($product_filter_box_answers as $product_filter_box_answer) {
            $product_filter_box_answer->delete();
          }
          $product_filter_boxes = ProductFilterBox::where('partner_product_id', '=', $product->id)->get();
          foreach ($product_filter_boxes as $product_filter_box) {
            $product_filter_box->delete();
          }

          // We don't forget to delete all the images associated to this entry
          $images = $product->images()->get();

          foreach ($images as $image) {

            delete_file($image->filename, $image->folder);
            $image->delete();
          }


        });

    }

  /**
   * Belongs To
   */
  
  public function partner()
  {

    return $this->belongsTo('App\Models\Partner');

  }

  /**
   * HasMany
   */
  
  public function images()
  {

    return $this->hasMany('App\Models\ProductImage');

  }

  public function filter_boxes()
  {

    return $this->hasMany('App\Models\ProductFilterBox');

  }

  public function filter_box_answers()
  {

    return $this->hasMany('App\Models\ProductFilterBoxAnswer');

  }

  public function serie_products()
  {

    return $this->hasMany('App\Models\SerieProduct');

  }

  public function user_profile_products()
  {

    return $this->hasMany('App\Models\UserProfileProduct');

  }

  public function master_product_childrens()
  {

    return $this->hasMany('App\Models\PartnerProduct', 'master_partner_product_id');

  }

  public function boxes()
  {

    // Will make a pivot table
    return $this->belongsToMany('Box', 'product_filter_boxes', 'partner_product_id', 'box_id');

  }


  /**
   * Other
   */
  
  public function scopeGetProductsWithException($query, $exception_id)
  {

    if ($exception_id === NULL) $exception_id = 0;

    return DB::select(DB::raw("

      SELECT *
      FROM partner_products
      WHERE deleted_at IS NULL
      AND id <> $exception_id
    
    "));

  }
  
  /**
   * We will get the first one of the master ids and all the nulls
   */
  public function scopeGetDistinctByMasterProducts($query, $exception_id)
  {

    if ($exception_id === NULL) $exception_id = 0;

    // NOTE : we keep this query but it was stupid to make it this way
    // Ths simpliest way is to select only the products that don't have masters (NULL), they are the ones.

    return DB::select(DB::raw("

      SELECT *
      FROM partner_products
      WHERE master_partner_product_id IS NOT NULL
      AND deleted_at IS NULL
      AND id <> $exception_id
      GROUP BY (master_partner_product_id)
    
    "));

    /*return DB::select(DB::raw("

      SELECT *
      FROM partner_products
      WHERE master_partner_product_id IS NOT NULL
      AND id <> $exception_id
      GROUP BY (master_partner_product_id)

      UNION

      SELECT *
      FROM partner_products
      WHERE master_partner_product_id IS NULL
    
    "));*/

    //return $query->whereNull('master_partner_product_id')->where('id', '!=', $exception_id);

  }

  public function getOriginalMaster()
  {

    $master_product = $this;

    // It's not the master one
    if ($master_product->master_partner_product_id !== $this->id) {

      // We redot everything
      $master_product = PartnerProduct::find($master_product->master_partner_product_id);
      return $master_product->getOriginalMaster();

    } else {

      // It's the one, we resolve it
      return $master_product->id;

    }

  }

  public function cloneAdvancedFiltersFromMaster($master_id=NULL)
  {

    if ($master_id === NULL) $master_id = $this->master_partner_product_id;

    // If there's a master
    if ($master_id !== $this->id) {

      $master_partner_product = PartnerProduct::find($master_id);

      if ($master_partner_product->filter_box_answers()->count() > 0) {

        // We clone the matching boxes advanced filters
        $allowed_boxes = [];
        foreach ($this->filter_boxes()->get() as $product_filter_box) {

          array_push($allowed_boxes, $product_filter_box->box_id);

        }

        // Now we will select the answers matching, but only if the box was selected
        $datas = $master_partner_product->filter_box_answers()
                ->join('box_questions', 'product_filter_box_answers.box_question_id', '=', 'box_questions.id')
                ->whereIn('box_questions.box_id', $allowed_boxes)
                ->select('product_filter_box_answers.*')
                ->get()->toArray();

        foreach ($datas as $data) {

          unset($data['id']);
          unset($data['created_at']);
          unset($data['updated_at']);

          $data['partner_product_id'] = $this->id;

          ProductFilterBoxAnswer::create($data);

        }

      } else return FALSE;

    }

  }

}