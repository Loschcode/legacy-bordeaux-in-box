<?php namespace App\Http\Controllers\Shared\Service;

use App\Http\Controllers\MasterBox\BaseController;

use App\Models\Customer;
use App\Models\EmailTrace;

class TracesController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Default Api Controller
  |--------------------------------------------------------------------------
  |
  | Api system
  |
  */

  /**
   * Resolve a specific partner product
   */
  public function postEmail()
  {

    // We get the post
    $datas = $_POST;

    // We get the event and recipient
    $recipient = $datas['recipient'];
    $event = $datas['event'];

    /*$email = new EmailTrace;
    $email->content = json_encode($datas);
    $email->save();*/

    // MailGun message ID (Yeah fuck you with your norms)
    if (isset($datas['Message-Id']))
      $mailgun_message_id = str_replace('>', '', str_replace('<', '', $datas['Message-Id']));

    elseif (isset($datas['message-id'])) 
      $mailgun_message_id = $datas['message-id'];

    if ($event === 'delivered') {

      // The important variable here
      if (isset($datas['email_trace_id'])) $email_trace_id = $datas['email_trace_id'];
      else $email_trace_id = NULL;

      if ($email_trace_id !== NULL) {

        // We have some secured variables (useless normally)
        $customer_id = $datas['customer_id'];
        $profile_id = $datas['profile_id'];

        $email_trace = EmailTrace::find($email_trace_id);

        if ($email_trace !== NULL) {

          $email_trace->delivered_at = date('Y-m-d H:i:s');
          $email_trace->mailgun_message_id = $mailgun_message_id;
          $email_trace->save();

        }

      }

    } elseif ($event === 'opened') {

      $email_trace = EmailTrace::where('mailgun_message_id', '=', $mailgun_message_id)->first();

      if ($email_trace !== NULL) {

        if ($email_trace->first_opened_at === NULL) {

          $email_trace->first_opened_at = date('Y-m-d H:i:s');

          $customer = $email_trace->customer()->first();

          if ($customer !== NULL) {

            // Authorization update
            if ($customer->emails_fully_authorized === NULL) {

              $customer->emails_fully_authorized =  date('Y-m-d H:i:s');
              $customer->save();

            }

          } 


        }

        $email_trace->last_opened_at = date('Y-m-d H:i:s');
        $email_trace->save();

      }

    }

    return response()->make('Trace succeeded.', 200);

  }

  public function getTestEmail()
  {

    $customer = Customer::where('email', '=', 'bonjour@laurentschaffner.com')->first();
    $profile = $customer->profiles()->first();

    $data = [

    'first_name' => 'Fake name',

    'series_date' => 'Fake series',

    'destination_address' => 'Fake destination',
    'billing_address' => 'Fake address',

    'gift' => FALSE,

    'box_title' => 'Fake box title'

    ];

    mailing_send($profile, 'Fake subject', 'emails.orders.shipped_delivered', $data, NULL);

    return response()->make('Test succeeded.', 200);

  }

}
