<?php namespace App\Http\Controllers\MasterBox\Service;

use App\Http\Controllers\MasterBox\BaseController;

use App\Models\Contact;
use App\Models\PartnerProduct;
use App\Models\DeliverySerie;
use App\Models\BoxQuestionCustomerAnswer;

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
    $this->middleware('is.admin', ['only' => ['getContacts', 'getOrdersCount']]);
    $this->middleware('is.customer', ['only' => ['postBoxQuestionCustomerAnswer']]);
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
   * Set new box question customer answer
   * @param string type The type of the question
   * @param string question_id The id of the question
   * @param mixed answer The answer/answers
   * @return json
   */
  public function postBoxQuestionCustomerAnswer()
  {
    $customer = Auth::guard('customer')->user();

    $order_building = $customer->order_building()->orderBy('created_at', 'desc')->onlyPaid()->first();
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
