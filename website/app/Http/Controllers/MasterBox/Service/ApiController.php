<?php namespace App\Http\Controllers\MasterBox\Service;

use App\Http\Controllers\MasterBox\BaseController;

use App\Models\Contact;
use App\Models\PartnerProduct;
use App\Models\DeliverySerie;
use App\Models\BoxQuestionCustomerAnswer;
use App\Models\CustomerProfile;
use App\Models\Customer;
use App\Models\Order;

use Auth, Validator;

class ApiController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Default Api Controller
  |--------------------------------------------------------------------------
  |
  | Api system
  |
  */
  public function __construct()
  {
    $this->middleware('is.admin', ['only' => ['getContacts', 'getOrdersCount', 'getProfiles', 'getCustomers']]);
    $this->middleware('is.customer', ['only' => ['postBoxQuestionCustomerAnswer']]);
  }

  public function getCustomers()
  {
    $draw = request()->input('draw');
    $start = request()->input('start');
    $search = request()->input('search')['value'];
    $length = request()->input('length');
    $order_column = request()->input('order')[0]['column'];
    $order_sort = request()->input('order')[0]['dir'];

    $columns = [
      '1' => 'customers.id',
      '2' => 'customers.first_name',
      '3' => 'customers.email',
      '4' => 'customers.phone'
    ];

    // Translate the order column
    $order_column = $columns[$order_column];

    $total_results = Customer::count();

    if (empty($search)) {

      $customers = Customer::with('profiles')
                           ->orderBy($order_column, $order_sort)
                           ->skip($start)
                           ->take($length)
                           ->orderBy('created_at', 'desc')
                           ->get();

      $total_results_after_filtered = $total_results;

    } else {

    //
    //\DB::enableQueryLog();

      $query = Customer::research($search);

      $total_results_after_filtered = $query->count();
      $customers = $query->orderBy($order_column, $order_sort)
                         ->skip($start)
                         ->take($length)
                         ->orderBy('created_at', 'desc')
                         ->get();


    }

         // dd(\DB::getQueryLog());

    return response()->json([
      'data' => $customers,
      'recordsTotal' => $total_results,
      'recordsFiltered' => $total_results_after_filtered,
      'draw' => (int) $draw
    ]);
  }

  /**
   * Get profiles for the data table admin profiles
   */
  public function getProfiles()
  {

    $draw = request()->input('draw');
    $start = request()->input('start');
    $search = request()->input('search')['value'];
    $length = request()->input('length');
    $order_column = request()->input('order')[0]['column'];
    $order_sort = request()->input('order')[0]['dir'];

    $column_label = [

      1 => 'customer_profiles.id',
      2 => 'customer_profiles.contract_id',

    ];

    $total_results = CustomerProfile::count();

    if (empty($search)) {

      $profiles = CustomerProfile::with(['customer', 'orders', 'payments'])->skip($start)->take($length);

      if (isset($column_label[$order_column]))
        $profiles = $profiles->orderBy($column_label[$order_column], $order_sort);

      $profiles = $profiles->select('customer_profiles.*')
                           ->orderBy('created_at', 'desc')
                           ->get();

      $total_results_after_filtered = $total_results;

    } else {
    
    //\DB::enableQueryLog();


     $query = CustomerProfile::research($search);

     if (isset($column_label[$order_column]))
        $query = $query->orderBy($column_label[$order_column], $order_sort);

      $total_results_after_filtered = $query->count();
      $profiles = $query->skip($start)
                        ->take($length)
                        ->select('customer_profiles.*')
                        ->orderBy('created_at', 'desc')
                        ->get();

         //dd(\DB::getQueryLog());

    }
 
    return response()->json([
      'data' => $profiles,
      'recordsTotal' => $total_results,
      'recordsFiltered' => $total_results_after_filtered,
      'draw' => (int) $draw
    ]);

  }

  /**
   * Resolve a specific partner product
   */
  public function postGetPartnerProduct($id)
  {
    $partner_product = PartnerProduct::find($id);

    if ($partner_product == NULL) {

      return response()->json(['success' => FALSE, 'error' => 'Impossible to find this product']);

    } else {

      return response()->json(['success' => TRUE, 'datas' => $partner_product]);
      
    }
  }

  /**
   * Fetch all contacts (emails)
   */
  public function getContacts()
  {
    $contacts = Contact::orderBy('created_at', 'asc')->get();
    return response()->json($contacts->toJson());
  }

  /**
   * Count the orders (for the bip system)
   */
  public function getOrdersCount()
  {
    $current_serie = DeliverySerie::nextOpenSeries()->first();

    $count = $current_serie->orders()->notCanceledOrders()->count();
    return response()->json(['count' => $count]);
  }


  /**
   * Will update the status of an order
   * @return json
   */
  public function postUpdateStatusOrder()
  {

    $rules = [

      'order_id' => 'required|numeric',
      'order_status' => 'required',

      ];

    $fields = request()->all();
    $validator = validator()->make($fields, $rules);

    if ( ! $validator->passes()) {
      return response()->json(['success' => FALSE, 'message' => 'Une erreur est apparu lors de la modification du statut.']);
    }

    $order = Order::find($fields['order_id']);
    $order->status = $fields['order_status'];
    $order->save();

    return response()->json(['success' => TRUE, 'message' => 'Le statut de la commande a été modifié']);

  }

  /**
   * Will update the payment type used for the order
   * @return json
   */
  public function postUpdatePaymentWayOrder()
  {

    $rules = [

      'order_id' => 'required|numeric',
      'order_payment_way' => 'required',

      ];

    $fields = request()->all();
    $validator = validator()->make($fields, $rules);

    if ( ! $validator->passes()) {
      return ['success' => FALSE, 'message' => 'Une erreur est apparu lors de la modification du type de paiement.'];
    }

    $order = Order::find($fields['order_id']);
    $order->payment_way = $fields['order_payment_way'];
    $order->save();

    return ['success' => TRUE, 'message' => 'Le statut de la commande a été modifié'];

  }

  /**
   * Set new box question customer answer
   * @param string type The type of the question
   * @param string question_id The id of the question
   * @param mixed answer The answer/answers
   * @return json
   */
  public function postBoxQuestionCustomerAnswer()
  {
    $customer = Auth::guard('customer')->user();

    $order_building = $customer->order_buildings()->getLastPaid()->first();
    $profile = $order_building->profile()->first();

    $inputs = request()->all();

    $rules = $this->boxQuestionCustomerAnswerRules($inputs['type'], $customer->email);

    $validator = Validator::make($inputs, $rules);

    if ($validator->passes()) {

      // We remove all the linked old answers
      $old_answers = $profile->answers()->where('box_question_id', '=', $inputs['question_id'])->get();
      foreach ($old_answers as $old_answer) {
        $old_answer->delete();
      }

      $answers = $inputs['answer'];

      // We convert to an array to norm it
      if ( ! is_array($answers)) {
        $answers = [$inputs['answer']];
      }

      // We add them in a row
      foreach ($answers as $answer) {

        if ( ! empty($answer)) {

          $customer_answer = new BoxQuestionCustomerAnswer;
          $customer_answer->box_question_id = $inputs['question_id'];
          $customer_answer->customer_profile_id = $profile->id;
          $customer_answer->answer = $answer;
          $customer_answer->save();

        }

      }

      return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'errors' => $validator->errors()->first('answer')]);

  }

  /**
   * Fetch the valid rules for the question type given
   * @param  string $question_type  Type of the question
   * @param  string $customer_email Email of the customer
   * @return array
   */
  private function boxQuestionCustomerAnswerRules($question_type, $customer_email)
  {
    if ($question_type === 'checkbox') return ['answer' => ''];
    if ($question_type === 'date') return ['answer' => ['', 'regex:#^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$#']];
    if ($question_type === 'member_email') return ['answer' => ['email', '', 'exists:users,email', 'not_in:'.$customer_email]];

    return ['answer' => ''];

  }


}
