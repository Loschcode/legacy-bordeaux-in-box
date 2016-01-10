<?php namespace App\Http\Controllers\MasterBox\Admin;

use App\Http\Controllers\MasterBox\BaseController;

use Carbon\Carbon; 

use App\Models\Partner;
use App\Models\PartnerProduct;
use App\Models\DeliverySerie;
use App\Models\Order;
use App\Models\CustomerProfileProduct;
use App\Models\ProductFilterSetting;
use App\Models\SerieProduct;
use App\Models\BlogArticle;
use App\Models\ProductFilterBoxAnswer;

use Request, Validator, Config;

class ProductsController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Admin Products Controller
  |--------------------------------------------------------------------------
  |
  | All about the products & partners
  |
  */

  /**
   * Filters
   */
  public function __construct()
  {

      $this->beforeMethod();

  }
    

  /**
   * Get the listing page
   * @return void
   */
  public function getIndex()
  {

    $partners = Partner::orderBy('created_at', 'desc')->get();

    $products = PartnerProduct::orderBy('created_at', 'desc')->get();

    $series = DeliverySerie::orderBy('delivery', 'asc')->get();

    $categories_list = ['0' => '-'] + Config::get('bdxnbx.product_categories');

    $product_sizes_list = Config::get('bdxnbx.product_sizes');

    $partners_list = $this->generate_partners_list();

    $products_list = $this->generate_products_list();

    $blog_articles_list = $this->generate_blog_articles_list();

    return view('master-box.admin.products.index')->with(compact(

      'partners',
      'products',
      'series',
      'categories_list',
      'product_sizes_list',
      'partners_list',
      'products_list',
      'blog_articles_list'

    ));

  }

  /**
   * Assign a new product to a specific profile for a serie
   */
  public function postAddProductToCustomerProfile()
  {

    
  }

  /**
   * We edit the products assigned to the user for a specific order
   */
  public function getEditProfileProducts($order_id)
  {

    $order = Order::findOrFail($order_id);

    $possible_serie_products = ['Nothing' => '-'];

    return view('master-box.admin.products.edit_profile_products')->with(compact(
      'order',
      'possible_serie_products'
    ));

  }

  /**
   * We delete the products assigned to the user for a specific order
   */
  public function getDeleteProfileProduct($profile_product_id)
  {

    $profile_product = CustomerProfileProduct::findOrFail($profile_product_id);
    $profile_product->delete();

    session()->flash('message', "Le produit a été correctement supprimé");
    return redirect()->back();

  }


  /**
   * We delete the products assigned to the user for a specific order
   */
  public function getDeleteProfileProducts($order_id)
  {

    $customer_profile_products = CustomerProfileProduct::where('order_id', '=', $order_id)->get();

    foreach ($customer_profile_products as $customer_profile_product) {

      $customer_profile_product->delete();

    }

    session()->flash('message', "Les produits assignés ont été supprimé");
    return redirect()->back();

  }

  public function getGenerateProductsSelection($serie_id)
  {

    Devlog::info("Le système se prépare à générer une sélection ...");

    $serie = DeliverySerie::find($serie_id);
    if ($serie == NULL) return redirect()->back();

    /**
     * We prepare the models
     */
    $product_filter_setting = $serie->product_filter_setting()->first();
    $serie->serie_products()->resetQuantityLeft(); // We reset the quantity left

    Devlog::info("Réinitialisation des quantités de produits pour la série");

    // We remove the old CustomerProfileProducts linked with this serie
    $serie_products = $serie->serie_products()->get();
    foreach ($serie_products as $serie_product) {
      $customer_profile_products = CustomerProfileProduct::where('serie_product_id', '=', $serie_product->id)->get();
      foreach ($customer_profile_products as $customer_profile_product) {
        $customer_profile_product->delete();
      }
    }

    Devlog::info("Réinitialisation des produits pour la série si nécessaire");

    // TODO : For the test we don't RAND() the orders to stay consistant -> WE MUST REMOVE IT AFTER
    $orders = $serie->orders()->get();
    //$orders = $serie->orders()->orderBy(DB::raw('RAND()'))->get(); // Right now it's rand to do not have favorited people

    /**
     * For each order of the serie, we will distribute all the serie products
     * Depending on the filters and settings
     */
    foreach ($orders as $order) {

      /**
       * First order
       * ID : 182
       * PROFILE : 102
       */
      
      // Preparation of the important datas from the profile, the order, etc.
      $profile = $order->customer_profile()->first();
      $box = $order->box()->first();
      $is_regional_order = $order->isRegionalOrder();
      $customer = $profile->user()->first();
      $customer_fullname = $customer->getFullName();

      // This variable will be used to avoid multiple product of the same kind for the user
      $anti_master_id_duplicated = [];

      Devlog::strong("Gestion de la commande #$order->id pour <a href='/admin/profiles/edit/$profile->id' target='_blank'>$customer_fullname</a>");
      Devlog::light("Box : $box->title");

      if ($is_regional_order) Devlog::light("Régional : Oui");
      else Devlog::light("Régional : Non");

      /**
       *
       * == STEP ONE ==
       *
       * 1.
       * X Filter by box -> Won't change, it's a basic condition for an order
       * X Filter by region -> If he's not in the region, we won't take any product outside
       *
       *
       * 2.
       * X Filter by advanced filters depending on the user -> Try to take the order the user will prefer first
       * X Select the order products with the products ordered by $score/$total with a score system and populate, throw away the "must-match" that didn't work
       * 
       * 3.
       * X Try to fill depending on the size (big, medium, small) ordered by score
       * X If products left to fill for this order -> Remove/Take the rejected one in the advanced filter and do the same until it's full (not the "must-match" obviously)
       * X We replace the products we already got at some point in the past (if we can, otherwise we let it like that)
       *
       * NOTE.
       * - During the process it would be interesting to catch the priority of the profile and order the products depending on it (more value etc. everything is already in)
       * 
       */
      
      /**
       * 1
       */
      
      Devlog::info("Pré-sélection de produits selon les critères de base (region, ready, box)");

      $basic_serie_products = $this->preselect_products_for_order($is_regional_order, $serie, $box);
      $basic_serie_products_ids_array = convert_model_object_to_ids_array($basic_serie_products);

      $total_products_available = count($basic_serie_products_ids_array);

      Devlog::success("Produits disponible : $total_products_available");

      /**
       * 2
       */
      
      // We repopulate the products we filtered from now
      $serie_products = SerieProduct::whereIn('id', $basic_serie_products_ids_array)->get();

      $products_accuracy = [];
      $ordered_products_accuracy = [];

      foreach ($serie_products as $serie_product) {

        $partner_product = $serie_product->product()->first();

        Devlog::info("Calcul pour le produit $partner_product->name (#$partner_product->id)");

        // We get the accuracy for this product
        $accuracy = $this->get_global_accuracy($serie_product, $profile);


        if ($accuracy === FALSE) Devlog::info("Pertinence finale : INACCEPTABLE");
        else Devlog::light("Pertinence finale : $accuracy");

        // We put it in the list (WARNING : this listing isn't sorted by accuracy or anything)
        $products_accuracy[$serie_product->id] = $accuracy;

      }

      // We order the array to start from 100 to 0
      arsort($products_accuracy);

      Devlog::info("Les produits ont été ordonnés par pertinence");

      // We will get the same list without the accuracy data, and with the unacceptable (FASLE) removed
      $acceptable_serie_products_ids_array = $this->get_acceptable_products_list($products_accuracy);

      $total_products_available = count($acceptable_serie_products_ids_array);
      Devlog::info("Les produits inacceptables ont été retirés de la sélection");
      Devlog::light("Produits restant : $total_products_available");

      /**
       * 3
       */
      
      Devlog::info("Fin des préparations.");
      Devlog::info("Démarrage du processus de génération pour la commande ...");
      
      /**
       * We will fetch all the products and add them
       */
      $large_products_left = $product_filter_setting->large_products;
      $medium_products_left = $product_filter_setting->medium_products;
      $small_products_left = $product_filter_setting->small_products;

      $already_inserted_products = [];

      foreach ($products_accuracy as $serie_product_id => $accuracy) {

        // If it's not a forbidden product
        if ($accuracy !== FALSE) {

          $serie_product = SerieProduct::find($serie_product_id);
          $partner_product = $serie_product->product()->first();

          if (isset($anti_master_id_duplicated[$partner_product->master_partner_product_id])) {
            Devlog::error("Le produit est équivalent à un produit déjà sélectionné pour la commande, il va être ignoré");
          }

          if (($serie_product->quantity_left > 0) && (!isset($anti_master_id_duplicated[$partner_product->master_partner_product_id]))) {

            // If we filled everything, we stop here, otherwise we keep adding products to the serie profile
            if ((($partner_product->size === 'maximum') && ($large_products_left > 0))
            or (($partner_product->size === 'medium') && ($medium_products_left > 0))
            or (($partner_product->size === 'minimum') && ($small_products_left > 0))) {

              // We check how many times we got this one
              $already_got = CustomerProfileProduct::where('customer_profile_id', '=', $profile->id)
                            ->where('partner_product_id', '=', $partner_product->id)
                            ->count();

              $customer_profile_product = new CustomerProfileProduct;

              // TODO : Here at some point we should order by value etc. depending on the user profile
              
              // We will add this product
              $customer_profile_product->customer_profile_id = $profile->id;
              $customer_profile_product->order_id = $order->id;
              $customer_profile_product->serie_product_id = $serie_product->id;
              $customer_profile_product->partner_product_id = $partner_product->id;
              $customer_profile_product->already_got = $already_got;
              $customer_profile_product->accuracy = $accuracy;
              $customer_profile_product->birthday = FALSE;
              $customer_profile_product->sponsor = FALSE;

              $customer_profile_product->save();

              $serie_product->quantity_left--;
              if ($serie_product->quantity_left <= 0) unset($acceptable_serie_products_ids_array[$serie_product->id]);
              $serie_product->save();

              $anti_master_id_duplicated[$partner_product->master_partner_product_id] = TRUE;

              Devlog::success("Ajout du produit $partner_product->name");
              Devlog::light("Quantité restantes : $serie_product->quantity_left");
              Devlog::light("Type de produit : $partner_product->size");

              array_push($already_inserted_products, $serie_product->id);

              // Then we put less
              if ($partner_product->size === 'maximum') $large_products_left--;
              elseif ($partner_product->size === 'medium') $medium_products_left--;
              elseif ($partner_product->size === 'minimum') $small_products_left--;

            } else {

              Devlog::info("Détection des produits ayant déjà été sélectionnés pour cet utilisateur par le passé ...");

              // We filled everything, let's try to replace the product already got
              $already_got_customer_profile_products = CustomerProfileProduct::where('customer_profile_id', '=', $profile->id)
                                                  ->where('serie_product_id', '=', $serie_product->id)
                                                  ->where('already_got', '>', 0)
                                                  ->get();

              foreach ($already_got_customer_profile_products as $already_got_customer_profile_product) {

                $partner_product = $already_got_customer_profile_product->product()->first();

                Devlog::success("Produit ayant déjà sélectionné détecté $partner_product->name ($already_got_customer_profile_product->already_got fois)");
                
                // We just have to avoid the products we already put in the list
                // The products that are already_got too
                // And try to find a new product that is in the basic listing
                
                $possible_serie_product = SerieProduct::whereIn('id', $acceptable_serie_products_ids_array)
                                                      ->whereNotIn('id', $already_inserted_products)->first();

                Devlog::info("Tentative de remplacement ...");
                                              
                // For now the algorithm take a random product from the acceptable products
                // Avoiding the ones we already put in our selection for the user
                // There's a small probability the `already_got` will be as bad as the other, but a small one.
                
                if ($possible_serie_product !== NULL) {

                  $partner_product = $possible_serie_product->product()->first();

                  if (isset($anti_master_id_duplicated[$partner_product->master_partner_product_id])) {
                    Devlog::error("Le produit est équivalent à un produit déjà sélectionné pour la commande, il va être ignoré");
                  }

                  if (!isset($anti_master_id_duplicated[$partner_product->master_partner_product_id])) {

                    $already_got = CustomerProfileProduct::where('customer_profile_id', '=', $profile->id)
                                  ->where('partner_product_id', '=', $partner_product->id)
                                  ->count();


                    $accuracy = $products_accuracy[$possible_serie_product->id];

                    // We will replace the product
                    $already_got_customer_profile_product->serie_product_id = $possible_serie_product->id;
                    $already_got_customer_profile_product->partner_product_id = $partner_product->id;
                    $already_got_customer_profile_product->already_got = $already_got;
                    $already_got_customer_profile_product->accuracy = $accuracy;

                    $already_got_customer_profile_product->save();

                    Devlog::success("Remplacement par produit $partner_product->name");
                    Devlog::light("Pertinence : $accuracy");
                    Devlog::light("Occurence : $already_got");

                    $possible_serie_product->quantity_left--;
                    if ($possible_serie_product->quantity_left <= 0) unset($acceptable_serie_products_ids_array[$possible_serie_product->id]);
                    $possible_serie_product->save();

                    $anti_master_id_duplicated[$partner_product->master_partner_product_id] = TRUE;

                    array_push($already_inserted_products, $possible_serie_product->id);

                  }

                }

              }

              // The end of the original loop
              break;

            }

          } // quantity_left 0

        } // accuracy false

      }

      /**
       * 
       * == STEP TWO ==
       *
       * 1.
       * - We will add birthday products, sponsor products (if he's sponsor or has a sponsor) ; only small products
       *
       *
       */
      $is_birthday = is_birthday($profile->getAnswer('birthday'));

      if ($is_birthday) {

        Devlog::success("Anniversaire de l'utilisateur détecté");
        Devlog::info("Processus d'ajout d'un produit ...");

        // It's her birthday, we will try to find a birthday product that is acceptable for her
        $selected_birthday_products = SerieProduct::joinProducts()
                                                  ->isBirthdayReady()
                                                  ->whereIn('serie_products.id', $acceptable_serie_products_ids_array)
                                                  ->whereNotIn('serie_products.id', $already_inserted_products)->get();

        $total_products_available = $selected_birthday_products->count();
        Devlog::light("Produits disponible pour anniversaire : $total_products_available");

        foreach ($selected_birthday_products as $selected_birthday_product) {

          // We refetch it because the condition was weird above
          $serie_product = SerieProduct::find($selected_birthday_product->id);

          // We get the accuracy for this product
          $accuracy = $this->get_global_accuracy($serie_product, $profile);

          if (isset($anti_master_id_duplicated[$partner_product->master_partner_product_id])) {
            Devlog::error("Le produit est équivalent à un produit déjà sélectionné pour la commande, il va être ignoré");
          }

          if (($accuracy !== FALSE) && ($serie_product->quantity_left > 0) && (!isset($anti_master_id_duplicated[$partner_product->master_partner_product_id]))) {

            $partner_product = $serie_product->product()->first();

            // We check how many times we got this one
            $already_got = CustomerProfileProduct::where('customer_profile_id', '=', $profile->id)
            ->where('partner_product_id', '=', $partner_product->id)
            ->count();

            // We will replace the product
            $customer_profile_product = new CustomerProfileProduct;

            // We will add this product
            $customer_profile_product->customer_profile_id = $profile->id;
            $customer_profile_product->order_id = $order->id;
            $customer_profile_product->serie_product_id = $serie_product->id;
            $customer_profile_product->partner_product_id = $partner_product->id;
            $customer_profile_product->already_got = $already_got;
            $customer_profile_product->accuracy = $accuracy;
            $customer_profile_product->birthday = TRUE;
            $customer_profile_product->sponsor = FALSE;

            $customer_profile_product->save();
            $serie_product->quantity_left--;
            if ($serie_product->quantity_left <= 0) unset($acceptable_serie_products_ids_array[$serie_product->id]);
            $serie_product->save();

            $anti_master_id_duplicated[$partner_product->master_partner_product_id] = TRUE;

            Devlog::success("Ajout du produit $partner_product->name");
            Devlog::light("Quantité restantes : $serie_product->quantity_left");
            Devlog::light("Type de produit : $partner_product->size");

            array_push($already_inserted_products, $serie_product->id);

            break;

          }

          }

      }

      $has_sponsor = $profile->isOrHasSponsor();

      if ($has_sponsor) {

        Devlog::success("Parrain de l'utilisateur détecté / utilisateur lui-même parrain");
        Devlog::info("Processus d'ajout d'un produit ...");

        $selected_sponsor_products = SerieProduct::joinProducts()
                                                  ->isSponsorReady()
                                                  ->whereIn('serie_products.id', $acceptable_serie_products_ids_array)
                                                  ->whereNotIn('serie_products.id', $already_inserted_products)->get();

        $total_products_available = $selected_sponsor_products->count();
        Devlog::light("Produits disponible pour parrain : $total_products_available");

        foreach ($selected_sponsor_products as $selected_sponsor_product) {

            // We refetch it because the condition was weird above
          $serie_product = SerieProduct::find($selected_sponsor_product->id);

            // We get the accuracy for this product
          $accuracy = $this->get_global_accuracy($serie_product, $profile);

          if (isset($anti_master_id_duplicated[$partner_product->master_partner_product_id])) {
            Devlog::error("Le produit est équivalent à un produit déjà sélectionné pour la commande, il va être ignoré");
          }

          if (($accuracy !== FALSE) && ($serie_product->quantity_left > 0) && (!isset($anti_master_id_duplicated[$partner_product->master_partner_product_id]))) {

            $partner_product = $serie_product->product()->first();

              // We check how many times we got this one
            $already_got = CustomerProfileProduct::where('customer_profile_id', '=', $profile->id)
            ->where('partner_product_id', '=', $partner_product->id)
            ->count();

              // We will replace the product
            $customer_profile_product = new CustomerProfileProduct;

              // We will add this product
            $customer_profile_product->customer_profile_id = $profile->id;
            $customer_profile_product->order_id = $order->id;
            $customer_profile_product->serie_product_id = $serie_product->id;
            $customer_profile_product->partner_product_id = $partner_product->id;
            $customer_profile_product->already_got = $already_got;
            $customer_profile_product->accuracy = $accuracy;
            $customer_profile_product->birthday = FALSE;
            $customer_profile_product->sponsor = TRUE;

            $customer_profile_product->save();

            $anti_master_id_duplicated[$partner_product->master_partner_product_id] = TRUE;

            $serie_product->quantity_left--;
            if ($serie_product->quantity_left <= 0) unset($acceptable_serie_products_ids_array[$serie_product->id]);
            $serie_product->save();

            Devlog::success("Ajout du produit $partner_product->name");
            Devlog::light("Quantité restantes : $serie_product->quantity_left");
            Devlog::light("Type de produit : $partner_product->size");

            array_push($already_inserted_products, $serie_product->id);

            break;

          }


        }

      }
      
      // RIGHT NOW THIS HASN'T BEEN DONE (FOR TECHNICAL REASON, THE FILTERS ARE WAY ENOUGH FOR NOW)

      /** == STEP THREE ==
       * 
       * - We get the average value of all the filled orders
       * 
       * - Priorities of the users -> We will exchange a few products depending on the user profile to have more value or less depending on the average + the % the user got (it has to be >= or <=)
       * - Priorities products have to match with the filters IF POSSIBLE
       * - We avoid to exchange prefered products.
       *
       * == NOTE ==
       *
       * - For each step, if it's impossible to get exactly what we want, we don't exchange at all.
       * 
       */

      $devlogs = Devlog::result();
      view()->share('devlogs', $devlogs);
      view()->share('serie', $serie);

      $this->layout->content = view()->make('admin.products.generate_products_selection');

    }

  }

  /**
   * From the products accuracy returned by the previous methods we will filters the non acceptable products (FALSE)
   * And return only the serie products ids
   * @param  array $products_accuracy
   * @return array         
   */
  private function get_acceptable_products_list($products_accuracy) {

    $final_array = [];

    foreach ($products_accuracy as $serie_product_id => $product_accuracy) {

      if ($product_accuracy !== FALSE) {

        array_push($final_array, $serie_product_id);

      }

    }

    return $final_array;

  }

  /**
   * Take a user profile and a serie product and get the global accuracy
   * @param  object $serie_product 
   * @param  object $profile      
   * @return mixed  
   */
  private function get_global_accuracy($serie_product, $profile) {

    // We prepare the datas
    $customer_profile_answers = $profile->answers()->get();
    $partner_product = $serie_product->product()->first();
    $product_filter_box_answers = $partner_product->filter_box_answers()->get();

    if (($product_filter_box_answers->first() === NULL) || ($customer_profile_answers->first() === NULL)) {

      // The product doesn't have any advanced filter, therefore it's not matching by default (it's the lowest priority)
      $accuracy = 0;

    } else {

      // Is the product advanced filters matching with the user form answers ?
      $matching_system = $this->get_accuracy_product_filters_matching_user_answer($product_filter_box_answers, $customer_profile_answers);

      if ($matching_system == FALSE) {

        $accuracy = FALSE;

      } else {

        $count_true = count(array_filter($matching_system, function ($n) { return $n === TRUE; }));
        $count_false = count(array_filter($matching_system, function ($n) { return $n === FALSE; }));

        $count_total = $count_true+$count_false;

        // Nothing matched, nothing was refused
        if ($count_total === 0) {

          $accuracy = 0;

        } else {

          // The total is the addition of FALSE and TRUE, we will ignore the NULL values
          $accuracy = ($count_true/($count_total)) * 100;

        }

      }

    }

    return $accuracy;

  }

  /**
   * We serialize the product filters to make it possible to compare with the user answers
   * @param  object $product_filter_box_answers
   * @return array 
   */
  private function serialize_product_filter_box_answers($product_filter_box_answers) {

    /**
     * We convert the data of the product filters to compare it to the user
     */
    foreach ($product_filter_box_answers as $answer) {

      $slug = $answer->slug;
      $answer_string = $answer->answer;
      $box_question_id = $answer->box_question_id;
      $filter_must_match = $answer->box_question()->first()->filter_must_match;

      $to_referent_slug = $answer->to_referent_slug;

      if ($to_referent_slug === NULL) {

        $ordered_filters[$box_question_id][] = [

        'slug' => $slug,
        'answer' => $answer_string,
        'filter_must_match' => $filter_must_match

        ];

      } else {

        $ordered_filters[$box_question_id][][$to_referent_slug] = [

        'slug' => $slug,
        'answer' => $answer_string,
        'filter_must_match' => $filter_must_match

        ];

      }

    }

    return $ordered_filters;

  }

  /**
   * From the object CustomerProfileAnswers we serialize every answers in an array to make it possible to compare with the filters
   * @param  object $customer_profile_answers
   * @return array   
   */
  private function serialize_customer_profile_answers($customer_profile_answers) {

    /**
     * We convert the data of the user answers to compare it to the product filters
     */
    foreach ($customer_profile_answers as $answer) {

      $slug = $answer->slug;
      $answer_string = $answer->answer;
      $box_question_id = $answer->box_question_id;

      $to_referent_slug = $answer->to_referent_slug;

      $referent_id = $answer->referent_id;

      if (empty($to_referent_slug)) {

        $customer_ordered_answers[$box_question_id][] = [

          'slug' => $slug,
          'answer' => $answer_string,

        ];

      } else {

        /**
         * System to convert the special fields
         */

        // It's a `date` field
        if ($to_referent_slug === 'date_age') {

          // We convert to the age
          $date_age = convert_date_to_age($answer_string);

          // Now we get the category
          $category = get_category_from_date_age($date_age);
          $category_slug = Str::slug($category);

          if ($category == FALSE) die('Exception on the category age. Please contact the developer.');

          if ($referent_id === NULL) {

            $customer_ordered_answers[$box_question_id][][$to_referent_slug] = [

              'slug' => $category_slug,
              'answer' => $category,

            ];

          } else {

            $customer_ordered_answers[$box_question_id][$referent_id][$to_referent_slug] = [

              'slug' => $category_slug,
              'answer' => $category,

            ];


          }

        // If it's a child_year, we can convert to child_age
        } elseif ($to_referent_slug === 'child_sex') {

          $child_sex = $answer_string;

          if ($referent_id === NULL) {

            $customer_ordered_answers[$box_question_id][]['child_sex'] = [

              'slug' => Str::slug($child_sex),
              'answer' => $child_sex,

            ];

          } else {

            $customer_ordered_answers[$box_question_id][$referent_id]['child_sex'] = [

              'slug' => Str::slug($child_sex),
              'answer' => $child_sex,

            ];

          }

        // If it's a child_year, we can convert to child_age
        } elseif ($to_referent_slug === 'child_year') {

          if (isset($child_month)) $answer_string = $child_month.'/'.$answer_string;
          $child_age = convert_date_to_age($answer_string);

          // Now we get the category (children case, different from a `date_age`)
          $category = get_category_from_children_special_fields($child_age);
          $category_slug = Str::slug($category);

          if ($category == FALSE) die('Exception on the category children age (year). Please contact the developer.');

          if ($referent_id === NULL) {

            $customer_ordered_answers[$box_question_id][]['child_age'] = [

              'slug' => $category_slug,
              'answer' => $category,

            ];

          } else {

            $customer_ordered_answers[$box_question_id][$referent_id]['child_age'] = [

                'slug' => $category_slug,
                'answer' => $category,

            ];

          }

        // If it's a child_year, we can convert to child_age
        } elseif ($to_referent_slug === 'child_month') {

          if (isset($child_year)) {

            $answer_string = $answer_string.'/'.$child_year;
            $child_age = convert_date_to_age($answer_string);

            // Now we get the category (children case, different from a `date_age`)
            $category = get_category_from_children_special_fields($child_age);
            $category_slug = Str::slug($category);

            if ($category == FALSE) die('Exception on the category children age (month). Please contact the developer.');

            if ($referent_id === NULL) {

              $customer_ordered_answers[$box_question_id][]['child_age'] = [

                'slug' => $category_slug,
                'answer' => $category,

              ];

            } else {

              $customer_ordered_answers[$box_question_id][$referent_id]['child_age'] = [

                  'slug' => $category_slug,
                  'answer' => $category,

              ];

            }

          }

        // Some case are simple fields within the special fields, so we use the normal process (e.g. `child_name`)
        } else {

          if ($referent_id === NULL) {

            $customer_ordered_answers[$box_question_id][][$to_referent_slug] = [

              'slug' => $slug,
              'answer' => $answer_string,

            ];

          } else {

            $customer_ordered_answers[$box_question_id][$referent_id]['child_age'] = [

                'slug' => $category_slug,
                'answer' => $category,

            ];

          }

        }

      }

    }

    return $customer_ordered_answers;

  }

  /**
   * Compare serialized filters with serialized answers and put a result
   * @param  array $ordered_filters     
   * @param  array $customer_ordered_answers
   * @return mixed                      
   */
  private function compare_serialized_filters_to_answers($ordered_filters, $customer_ordered_answers) {

    /**
     * Now we compare the two fields and put some maths
     */
    foreach ($customer_ordered_answers as $question_id => $customer_ordered_answer) {

      $match = NULL;
      $answer_cant_match = FALSE;
      $skip_everything_if_not_matching = FALSE;

      // No filter for this question ? We don't count it
      if (isset($ordered_filters[$question_id])) {

        // It's an array
        if (is_array($customer_ordered_answer)) {

          foreach ($ordered_filters[$question_id] as $ordered_filter) {

            if (is_array($ordered_filter)) {

              foreach ($ordered_filter as $to_referent_slug => $ordered_filter_referent_slug) {

                if (empty($to_referent_slug)) {

                  foreach ($customer_ordered_answer as $customer_answer_array) {

                    $customer_answer_slug = $customer_answer_array['slug'];
                    $filter_answer_slug = $ordered_filter_referent_slug['slug'];
                    $filter_must_match = $ordered_filter_referent_slug['filter_must_match'];

                    // Does it matches ?
                    if ($customer_answer_slug === $filter_answer_slug) {

                      // It matches, we don't need to go further
                      $match = TRUE;
                      if ($filter_must_match == TRUE) $answer_cant_match = FALSE;

                      break;

                    } else {

                      $match = FALSE;

                      // It doesn't match
                      if ($filter_must_match == TRUE) $answer_cant_match = TRUE;


                    }

                  }

                } else {

                  foreach ($customer_ordered_answer as $referent_id => $customer_answer_array) {

                    // Sometimes the user don't answer everything (like for the kids)
                    if (isset($customer_answer_array[$to_referent_slug]['slug'])) {

                      $customer_answer_slug = $customer_answer_array[$to_referent_slug]['slug'];

                      // Then we get the filter result
                      $filter_answer_slug = $ordered_filter_referent_slug['slug'];
                      $filter_must_match = $ordered_filter_referent_slug['filter_must_match'];

                      // Activate this when you want a field to be mandatory
                      $skip_everything_if_not_matching = FALSE;

                      /**
                       * Children special case
                       */
                      if ($to_referent_slug === 'child_name') {

                        $skip_everything_if_not_matching = TRUE;

                      }

                      if ($to_referent_slug === 'child_sex') {

                        $skip_everything_if_not_matching = TRUE;

                      }

                      // Does it matches ?
                      if ($customer_answer_slug === $filter_answer_slug) {

                        // It matches, we don't need to go further
                        $match = TRUE;
                        if ($filter_must_match == TRUE) $answer_cant_match = FALSE; // If there were some `answer_cant_match` before we cancel it because it matches
                        break;

                      } else {

                        $match = FALSE;

                        // It doesn't match
                        if ($filter_must_match == TRUE) $answer_cant_match = TRUE;
                        if ($skip_everything_if_not_matching) break;

                      }

                    }

                  }

                }

              }

              if ($match === TRUE) break;
              if ($skip_everything_if_not_matching) break;

            }

          }

        }

      }

    $results[$question_id] = $match;

    // We don't need to continue, the answer cannot match
    if ($answer_cant_match === TRUE) return FALSE;

    }

    return $results;

  }

  /**
   * Compare the product filters with the user answers and tell us if it matches, and how much does it match (accuracy)
   * 
   * @param  object  $product_filter_box_answers
   * @param  object  $customer_answers              
   * @return integer if it returns FALSE, the product isn't acceptable for this user
   */
  private function get_accuracy_product_filters_matching_user_answer($product_filter_box_answers, $customer_profile_answers)
  {

    /**
     * We serialize the filters and user answers
     */
    $ordered_filters = $this->serialize_product_filter_box_answers($product_filter_box_answers);
    $customer_ordered_answers = $this->serialize_customer_profile_answers($customer_profile_answers);

    /**
     * We compare them and return the results
     */
    $results = $this->compare_serialized_filters_to_answers($ordered_filters, $customer_ordered_answers);

    return $results;

  }

  public function getCustomizeProductsSelection($product_filter_setting_id)
  {

    $product_filter_setting = ProductFilterSetting::find($product_filter_setting_id);

    $serie = $product_filter_setting->delivery_serie()->first();

    return view('master-box.admin.products.setup_selection.customize')->with(compact(
      'product_filter_setting',
      'serie'
    ));

  }

  public function postCustomizeProductsSelection()
  {

    // New article rules
    $rules = [

      'products' => 'required|array',

      ];

    $fields = Request::all();

    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      foreach ($fields['products'] as $product_id => $datas) {

        $serie_product = SerieProduct::find($product_id);

        if ($serie_product !== NULL) {

          if (!$datas['quantity']) {

            // Something isn't filled
            return redirect()->back()
            ->withInput()
            ->withErrors(['Certaines valeurs n\'ont pas été remplies correctement']);

          }

          if (!$datas['cost_per_unity']) $datas['cost_per_unity'] = 0.0;
          if (!$datas['value_per_unity']) $datas['value_per_unity'] = 0.0;

          $serie_product->cost_per_unity = $datas['cost_per_unity'];
          $serie_product->value_per_unity = $datas['value_per_unity'];
          $serie_product->quantity = $datas['quantity'];
          $serie_product->ready = TRUE;
          $serie_product->save();

        }

      }

      return redirect()->to('/admin/products#filters')
      ->with('message', 'Personnalisation des produits terminée');

    } else {

      // We return the same page with the error and saving the input datas
      return redirect()->back()
      ->withInput()
      ->withErrors($validator);

    }



  }

  public function getUpdateProductsSelection($product_filter_setting_id)
  {

    $product_filter_setting = ProductFilterSetting::find($product_filter_setting_id);

    $serie = $product_filter_setting->delivery_serie()->first();

    $products = PartnerProduct::orderBy('slug', 'asc')->get();

    return view('master-box.admin.products.setup_selection.edit')->with(compact(
      'product_filter_setting',
      'serie',
      'products'
    ));

  }

  public function getSetupProductsSelection($id)
  {

    $serie = DeliverySerie::find($id);

    $products = PartnerProduct::orderBy('slug', 'asc')->get();

    return view('master-box.admin.products.setup_selection.new')->with(compact(
      'serie',
      'products'
    ));

  }

  public function postSetupProductsSelection()
  {

    // New article rules
    $rules = [

      'serie_id' => 'required|integer',

      'large_products' => 'required|integer',
      'medium_products' => 'required|integer',
      'small_products' => 'required|integer',

      'max_desired_cost' => 'numeric',
      'max_desired_weight' => 'numeric',

      'high_priority_difference' => 'required|integer',
      'low_priority_difference' => 'required|integer',

      'products' => 'required|array',
      'product_filter_setting_id' => 'integer', 

      ];

    $fields = Request::all();

    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $serie = DeliverySerie::find($fields['serie_id']);
      if ($serie == NULL) return redirect()->back();

      if (isset($fields['product_filter_setting_id'])) $edit = TRUE;
      else $edit = FALSE;

      if ($edit) $product_filter_setting = ProductFilterSetting::find($fields['product_filter_setting_id']);
      else $product_filter_setting = new ProductFilterSetting;

      $product_filter_setting->delivery_serie_id = $serie->id;
      $product_filter_setting->large_products = $fields['large_products'];
      $product_filter_setting->medium_products = $fields['medium_products'];
      $product_filter_setting->small_products = $fields['small_products'];

      $product_filter_setting->max_desired_cost = $fields['max_desired_cost'];
      $product_filter_setting->max_desired_weight = $fields['max_desired_weight'];

      $product_filter_setting->high_priority_difference = $fields['high_priority_difference'];
      $product_filter_setting->low_priority_difference = $fields['low_priority_difference'];

      $product_filter_setting->save();

      if ($edit) {

        // We remove all the serie products from the previous edition
        $serie_products = SerieProduct::where('delivery_serie_id', '=', $serie->id)->get();
        foreach ($serie_products as $serie_product) {
          $serie_product->delete();
        }

      }

      foreach ($fields['products'] as $product_id) {

        $partner_product = PartnerProduct::find($product_id);

        // We will compare to the last series and repopulate if we can
        $similar_serie_product = SerieProduct::where('partner_product_id', '=', $partner_product->id)->first();

        $serie_product = new SerieProduct;
        $serie_product->partner_product_id = $partner_product->id;
        $serie_product->delivery_serie_id = $serie->id;
        $serie_product->quantity = 0;
        $serie_product->ready = FALSE;
        $serie_product->save();

      }

      return redirect()->to('/admin/products#filters')
      ->with('message', 'Sélection de produits paramétrée');

    } else {

      // We return the same page with the error and saving the input datas
      return redirect()->back()
      ->withInput()
      ->withErrors($validator);

    }


  }

  public function getAdvancedProductFilters($id)
  {

    $product = PartnerProduct::find($id);
    $filter_boxes = $product->filter_boxes()->get();
    $filter_box_answers = $product->filter_box_answers()->get();

    // This method exists to check the checkboxes and the inputs
    $autofill_checkboxes = [];
    $autofill_texts = [];

    foreach ($filter_box_answers as $filter_box_answer) {

      $question_id = $filter_box_answer->box_question_id;
      $slug = $filter_box_answer->slug;
      $answer = $filter_box_answer->answer;
      $to_referent_slug = $filter_box_answer->to_referent_slug;

      if (empty($to_referent_slug)) {

        $autofill_texts[$question_id] = $answer;
        $autofill_checkboxes[$question_id][$answer] = TRUE;

      } else {

        $autofill_texts[$question_id][$to_referent_slug] = $answer;
        $autofill_checkboxes[$question_id][$to_referent_slug][$answer] = TRUE;

      }

    }

    return view('master-box.admin.products.advanced_filters')->with(compact(
      'autofill_checkboxes',
      'autofill_texts',
      'product',
      'filter_boxes'
    ));

  }

  public function postAdvancedProductFilters()
  {

    // New article rules
    $rules = [

      'product_id' => 'required|integer',

      ];

    $fields = Request::all();

    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $product = PartnerProduct::find($fields['product_id']);
      if ($product == NULL) return redirect()->back();

      // We delete every old filter to stay clean
      $old_filter_box_answers = ProductFilterBoxAnswer::where('partner_product_id', '=', $product->id)->get();
      foreach ($old_filter_box_answers as $filter_box_answer) {

        $filter_box_answer->delete();

      }

      // Now we will fetch everything
      foreach ($fields as $question_id => $filter) {

        // We get only the filters, not _token or anything else
        if (is_integer($question_id)) {

          // If filter is an array, it's checkboxes, otherwise it's normal field
          if (is_array($filter)) {

            foreach ($filter as $label => $answer) {

              $product_filter_box_answer = new ProductFilterBoxAnswer;
              $product_filter_box_answer->partner_product_id = $product->id;
              $product_filter_box_answer->box_question_id = $question_id;

              /**
               * Children fields / Date special case
               */
              if (($label === 'child_name') && (!empty($answer))) {

                $product_filter_box_answer->answer = $answer;
                $product_filter_box_answer->to_referent_slug = 'child_name';
                $product_filter_box_answer->save();

              } elseif (($label === 'date_age') && (is_array($answer))) {

                foreach ($answer as $date_age) {

                  $answer = $date_age;

                  $product_filter_box_answer = new ProductFilterBoxAnswer;
                  $product_filter_box_answer->partner_product_id = $product->id;
                  $product_filter_box_answer->box_question_id = $question_id;
                  $product_filter_box_answer->to_referent_slug = 'date_age';
                  $product_filter_box_answer->answer = $answer;
                  $product_filter_box_answer->save();

                }

              } elseif (($label === 'child_sex') && (is_array($answer))) {

                foreach ($answer as $child_sex) {

                  $answer = $child_sex;

                  $product_filter_box_answer = new ProductFilterBoxAnswer;
                  $product_filter_box_answer->partner_product_id = $product->id;
                  $product_filter_box_answer->box_question_id = $question_id;
                  $product_filter_box_answer->to_referent_slug = 'child_sex';
                  $product_filter_box_answer->answer = $answer;
                  $product_filter_box_answer->save();

                }

              } elseif (($label == 'child_age') && (is_array($answer))) {

                foreach ($answer as $child_age) {

                  $answer = $child_age;

                  $product_filter_box_answer = new ProductFilterBoxAnswer;
                  $product_filter_box_answer->partner_product_id = $product->id;
                  $product_filter_box_answer->box_question_id = $question_id;
                  $product_filter_box_answer->to_referent_slug = 'child_age';
                  $product_filter_box_answer->answer = $answer;
                  $product_filter_box_answer->save();

                }

              /**
               * End of children fields special case
               */
              } else {

                if (!empty($answer)) {

                  $product_filter_box_answer->answer = $answer;
                  $product_filter_box_answer->save();

                }

              }

            }

          } else {

            // Empty inputs don't count
            if (!empty($filter)) {

              $answer = $filter;

              $product_filter_box_answer = new ProductFilterBoxAnswer;
              $product_filter_box_answer->partner_product_id = $product->id;
              $product_filter_box_answer->box_question_id = $question_id;
              $product_filter_box_answer->answer = $answer;
              $product_filter_box_answer->save();

            }

          }

        }

      }

      return redirect()->to('/admin/products#products')
      ->with('message', 'Filtres avancés mis à jour pour le produit');

    } else {

      // We return the same page with the error and saving the input datas
      return redirect()->to('/admin/products#products')
      ->withInput()
      ->withErrors($validator);

    }

  }

  public function postAddPartner()
  {

    // New article rules
    $rules = [

      'name' => 'required|min:3',
      'description' => 'required|min:5',
      'blog_article_id' => 'integer',
      'images' => 'array',
      'website' => '',
      'facebook' => '',

      ];

    $fields = Request::all();

    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $partner = new Partner;

      $partner->name = $fields['name'];
      $partner->description = $fields['description'];
      $partner->website = $fields['website'];
      $partner->facebook = $fields['facebook'];

      if ($fields['blog_article_id']) $partner->blog_article_id = $fields['blog_article_id'];

      $partner->save();

      foreach ($fields['images'] as $image) {

        if ($image !== NULL) {

          upload_image($image, 'partners', new PartnerImage, $partner->name, ['partner_id' => $partner->id]);

        }

      }

      return redirect()->to('/admin/products#partners')
      ->with('message', 'Nouveau partenaire ajouté');

    } else {

      // We return the same page with the error and saving the input datas
      return redirect()->to('/admin/products#partners')
      ->withInput()
      ->withErrors($validator);

    }

  }

  public function postAddProduct()
  {

    // New article rules
    $rules = [

      'partner_id' => 'required|integer|not_in:0',
      'master_partner_product_id' => 'required|integer',
      'past_advanced_filters' => '',
      'name' => 'required|min:5',
      'category' => 'required|not_in:0',
      'description' => 'required|min:5',
      'size' => 'required',
      'weight' => 'required|numeric',
      'boxes' => 'array',
      'images' => 'array',

      'birthday_ready' => '',
      'sponsor_ready' => '',

      'regional_only' => '',

      ];

    $fields = Request::all();

    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $product = new PartnerProduct;

      $product->partner_id = $fields['partner_id'];
      $product->category = $fields['category'];
      $product->name = $fields['name'];
      $product->description = $fields['description'];
      $product->weight = $fields['weight'];
      $product->size = $fields['size'];

      /**
       * All the ready system
       */
      if (isset($fields['birthday_ready'])) $product->birthday_ready = TRUE;
      else $product->birthday_ready = FALSE;

      if (isset($fields['sponsor_ready'])) $product->sponsor_ready = TRUE;
      else $product->sponsor_ready = FALSE;

      /**
       * All the only system
       */
      if (isset($fields['regional_only'])) $product->regional_only = TRUE;
      else $product->regional_only = FALSE;

      // We don't forget to save everything now
      $product->save();

      // We will manage the master partner product id if it has one
      if ($fields['master_partner_product_id'] != "0") {

        // If the partner product linked has already a master
        // Within the model we will manage it automatically
        $product->master_partner_product_id = $fields['master_partner_product_id'];

      } else {

        $product->master_partner_product_id = $product->id;

      }

      $product->save();

      /**
       * Now the boxes ready system
       */
      if (isset($fields['boxes'])) {

        foreach ($fields['boxes'] as $label => $box_id) {

          $product_filter_box = ProductFilterBox::create([

            'box_id' => $box_id,
            'partner_product_id' => $product->id,

            ]);

        }

      }

      if (isset($fields['past_advanced_filters'])) {

        // We can now clone the advanced filters if needed (the master that the user chose, not the original master)
        $product->cloneAdvancedFiltersFromMaster($fields['master_partner_product_id']);

      } 

      /**
       * Then the image system
       */
      foreach ($fields['images'] as $image) {

        if ($image !== NULL) {

          upload_image($image, 'products', new ProductImage, $product->name, ['partner_product_id' => $product->id]);

        }

      }

      return redirect()->to('/admin/products#products')
      ->with('message', 'Nouveau produit ajouté');

    } else {

      // We return the same page with the error and saving the input datas
      return redirect()->back()
      ->withInput()
      ->withErrors($validator);

    }

  }

  /**
   * We a edit a product
   */
  public function getEditProduct($id)
  {

    $product = PartnerProduct::find($id);

    $products_list = $this->generate_products_list();

    $categories_list = ['0' => '-'] + Config::get('bdxnbx.product_categories');

    $product_sizes_list = Config::get('bdxnbx.product_sizes');

    $partners_list = $this->generate_partners_list();

    if ($product !== NULL)
    {

      return view('master-box.admin.products.edit')->with(compact(
        'products_list',
        'categories_list',
        'product_sizes_list',
        'partners_list',
        'product'
      ));

    }


  }


  /**
   * We a edit a partner
   */
  public function getEditPartner($id)
  {
    $partner = Partner::find($id);

    if ($partner !== NULL) {

      $blog_articles_list = $this->generate_blog_articles_list();

      return view('master-box.admin.products.partners.edit')->with(compact(
        'blog_articles_list',
        'partner'
      ));

    }
  }

  public function postEditPartner()
  {

    // New article rules
    $rules = [

      'partner_id' => 'required|not_in:0|integer',
      'name' => 'required|min:5',
      'description' => 'required|min:5',
      'blog_article_id' => 'integer',
      'edit_images' => 'array',
      'images' => 'array',
      'boxes' => 'array',
      'website' => '',
      'facebook' => '',

      ];

    $fields = Request::all();
    if (!isset($fields['edit_images'])) $fields['edit_images'] = []; // Laravel isn't so smart at the end

    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $partner = Partner::find($fields['partner_id']);

      if ($partner !== NULL) {

      $partner->name = $fields['name'];
      $partner->description = $fields['description'];
      $partner->website = $fields['website'];
      $partner->facebook = $fields['facebook'];

      if ($fields['blog_article_id']) $partner->blog_article_id = $fields['blog_article_id'];
      else $partner->blog_article_id = NULL;

      // We manage the old images
      $partner_images = $partner->images()->get();

      foreach ($partner_images as $image) {

        $image_id = $image->id;
        if (!isset($fields['edit_images'][$image_id])) $image->delete();

      }

      // Now we manage the new images
      foreach ($fields['images'] as $image) {

        if ($image !== NULL) {

          $file = $image;
          upload_image($file, 'partners', new PartnerImage, $partner->name, ['partner_id' => $partner->id]);

        }

      }

      return redirect()->to('/admin/products#partners')
      ->with('message', 'Partenaire edité');

      }

    } else {

      // We return the same page with the error and saving the input datas
      return redirect()->to('/admin/products#partners')
      ->withInput()
      ->withErrors($validator);

    }

  }

  public function postEditProduct()
  {

    // New article rules
    $rules = [

      'partner_id' => 'required|integer',
      'product_id' => 'required|integer',

      'master_partner_product_id' => 'required|integer',

      'name' => 'required|min:5',
      'description' => 'required|min:5',
      'category' => 'required|not_in:0',
      'edit_images' => 'array',
      'images' => 'array',
      'size' => 'required',
      'weight' => 'required|numeric',

      'birthday_ready' => '',
      'sponsor_ready' => '',

      'regional_only' => '',

      ];

    $fields = Request::all();
    if (!isset($fields['edit_images'])) $fields['edit_images'] = []; // Laravel isn't so smart at the end

    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $product = PartnerProduct::find($fields['product_id']);

      if ($product !== NULL) {

        $product->partner_id = $fields['partner_id'];
        $product->name = $fields['name'];
        $product->category = $fields['category'];
        $product->description = $fields['description'];
        $product->size = $fields['size'];
        $product->weight = $fields['weight'];

        /**
         * All the ready system
         */
        if (isset($fields['birthday_ready'])) $product->birthday_ready = TRUE;
        else $product->birthday_ready = FALSE;

        if (isset($fields['sponsor_ready'])) $product->sponsor_ready = TRUE;
        else $product->sponsor_ready = FALSE;

        /**
         * All the only system
         */
        if (isset($fields['regional_only'])) $product->regional_only = TRUE;
        else $product->regional_only = FALSE;

        // We don't forget to save everything now
        $product->save();

        // We will manage the master partner product id if it has one
        if ($fields['master_partner_product_id'] == "0") {

          $product->master_partner_product_id = $product->id;

        } else {

          // If the partner product linked has already a master
          // Within the model we will manage it automatically
          $product->master_partner_product_id = $fields['master_partner_product_id'];

        }

        $product->save();

        /**
         * Now the boxes ready system
         */
        
        if (isset($fields['boxes'])) {

          // We delete what isn't inside the array
          foreach ($product->filter_boxes()->get() as $product_filter_box) {

            // We will remove every box not selected
            if (!in_array($product_filter_box->box()->first()->id, $fields['boxes'])) {

              $product_filter_box->delete();

            }

          }

          foreach ($fields['boxes'] as $label => $box_id) {

            // We avoid double entries
            if ($product->filter_boxes()->where('box_id', '=', $box_id)->first() === NULL) {

              $product_filter_box = ProductFilterBox::create([

                'box_id' => $box_id,
                'partner_product_id' => $product->id,

                ]);

            }

          }

        } else {

          // We delete every old entries only if the user decided to remove everything
          foreach ($product->filter_boxes()->get() as $product_filter_box) {

            $product_filter_box->delete();

          }

        }

        // We manage the old images
        $product_images = $product->images()->get();

        foreach ($product_images as $image) {

          $image_id = $image->id;
          if (!isset($fields['edit_images'][$image_id])) $image->delete();

        }

        // Now we manage the new images
        foreach ($fields['images'] as $image) {

          if ($image !== NULL) {

            $file = $image;
            upload_image($file, 'products', new ProductImage, $product->name, ['partner_product_id' => $product->id]);

          }

        }

        return redirect()->to('/admin/products#products')
        ->with('message', 'Produit edité');

      }

    } else {

      // We return the same page with the error and saving the input datas
      return redirect()->to('/admin/products#products')
      ->withInput()
      ->withErrors($validator);

    }

  }

  public function getDeletePartner($id)
  {

    $partner = Partner::find($id);

    if ($partner !== NULL)
    {

      $partner->delete();

      session()->flash('message', "Le partenaire a été correctement archivé");
      return redirect()->to('/admin/products#partners');


    }

  }

  public function getDeleteProduct($id)
  {

    $product = PartnerProduct::find($id);

    if ($product !== NULL) {

      $product->delete();

      session()->flash('message', "Le produit a été correctement archivé");
      return redirect()->to('/admin/products#products');

    }

  }

  private function preselect_products_for_order($is_regional_order, $serie, $box)
  {

      // We will filter the basic stuff first (it won't change afterwards in the process)
      $basic_serie_products_query = $serie->serie_products()
                                    ->joinProducts()
                                    ->isReady() // Only the ready orders
                                    ->onlyBox($box); // Only matching with this box

      // If the guy isn't from the region, we will absolutely avoid all the regional products
      if (!$is_regional_order) {

        $basic_serie_products_query = $basic_serie_products_query
                                      ->notRegional();

      }

      // We get the first datas
      // Those datas are the basic products the order will have
      // It cannot be any other product because it wouldn't match with him (regional, box, ready)
      $basic_serie_products = $basic_serie_products_query->selectOnlyIds()->get();

      return $basic_serie_products;

  }

  private function generate_products_list($exception_id=NULL)
  {

    $products_list = [0 => '-'];

    // We will get the similar product, we distinct them by master product
    //$products = PartnerProduct::getDistinctByMasterProducts($exception_id);
    $products = PartnerProduct::getProductsWithException($exception_id);

    foreach ($products as $product) {

      $product_id = $product->id;
      $products_list[$product_id] = $product->name;

    }

    return $products_list;

  }

  private function generate_partners_list()
  {

    $partners_list = [0 => '-'];

    $partners = Partner::get();
    foreach ($partners as $partner) {

      $partner_id = $partner->id;
      $partners_list[$partner_id] = $partner->name;

    }

    return $partners_list;

  }

  private function generate_blog_articles_list()
  {

    $blog_articles_list = [0 => 'Article de blog dédié (Optionnel)'];

    $blog_articles = BlogArticle::get();
    foreach ($blog_articles as $blog_article) {

      $blog_article_id = $blog_article->id;
      $blog_articles_list[$blog_article_id] = $blog_article->title;

    }

    return $blog_articles_list;

  }

}