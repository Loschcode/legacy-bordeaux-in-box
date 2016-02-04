<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Acceptance tests for emails. Run that class and
 * You will receive on mailtrap.io each email's views of the 
 * project with real datas. It's useful to test a new email you built or to 
 * see if you broke something.
 */
class EmailsDesignTest extends TestCase
{

  /**
   * Hook laravel setup under phpunit
   */
  protected function refreshApplication()
  {
    // Hook env mail configuration to send messages by mailtrap
    // I'm hooking everything to be sure we will never 
    // send a message to a real user. Paranoiac stuff.
    // In fact only MAIL_DRIVER is required.
    putenv('MAIL_DRIVER=smtp');
    putenv('MAIL_HOST=mailtrap.io');
    putenv('MAIL_PORT=2525');
    putenv('MAIL_USERNAME=5287667f211735eec');
    putenv('MAIL_PASSWORD=e08e5c4f172d1c');

    parent::refreshApplication();
  }

  /** @test */
  public function email_general()
  {    
    $this->sendEmail('masterbox.emails.general', ['content' => $this->faker->paragraph(10)], 'masterbox.emails.general');
  }

  /** @test */
  public function email_transaction()
  {
    $this->sendEmail('masterbox.emails.transaction', [
      'first_name' => $this->faker->email,
      'amount' => rand(1, 50) . '&euro;',
      'paid' => true
    ], 'masterbox.emails.transaction - [$paid=true]');

    $this->sendEmail('masterbox.emails.transaction', [
      'first_name' => $this->faker->email,
      'amount' => rand(1, 50) . '&euro;',
      'paid' => false
    ], 'masterbox.emails.transaction - [$paid=false]');
  }

  /** @test */
  public function email_contact()
  {
    $this->sendEmail('masterbox.emails.contact', [
      'contact_email' => $this->faker->email,
      'contact_service' => $this->faker->sentence(),
      'contact_message' => $this->faker->paragraph(10)
    ], 'masterbox.emails.contact');
  }

  /** @test */
  public function email_subscription_expired()
  {
     $this->sendEmail('masterbox.emails.subscription.expired', [
       'first_name' => $this->faker->firstName,
       'last_box_was_sent' => false
     ], 'masterbox.emails.subscription.expired - [$last_box_was_sent = false]');

     $this->sendEmail('masterbox.emails.subscription.expired', [
       'first_name' => $this->faker->firstName,
       'last_box_was_sent' => true
     ], 'masterbox.emails.subscription.expired - [$last_box_was_sent = true]');   

  }

  /** @test */
  public function email_spots_transfer()
  {
    $new_spot = App\Models\DeliverySpot::orderBy(DB::raw('RAND()'))->first();

    $this->sendEmail('masterbox.emails.spots.transfer', [
      'first_name' => $this->faker->firstName,
      'old_spot_name' => App\Models\DeliverySpot::orderBy(DB::raw('RAND()'))->first()->name,
      'new_spot_name' => $new_spot->name,
      'new_spot_name_and_infos' => $new_spot->emailReadableSpot(),
      'new_spot_schedule' => nl2br($new_spot->schedule)
    ], 'masterbox.emails.spots.transfer');   
  }

  /** @test */
  public function email_orders_shipped_delivered()
  {

    $series = App\Models\DeliverySerie::orderBy(DB::raw('RAND()'))->whereNotNull('closed')->first();
    $orders = $series->orders()->DeliveredOrders()->where('take_away', '=', false)->orderBy(DB::raw('RAND()'))->take(4)->get();
    
    foreach ($orders as $order) {

      // To simulate a little bit the controller who sends it
      $destination = $order->destination()->first();
      $billing = $order->billing()->first();

      if ($destination == NULL) $destination_address = FALSE;
      else $destination_address = $destination->emailReadableDestination();

      if ($billing == NULL) $billing_address = FALSE;
      else $billing_address = $billing->emailReadableBilling();

      $customer = $order->customer()->first();
      // End simulate

      $this->sendEmail('masterbox.emails.orders.shipped_delivered', [
        'first_name' => $customer->first_name,
        'series_date' => $series->delivery,
        'destination_address' => $destination_address,
        'billing_address' => $billing_address,
        'gift' => $order->gift,
      ], 'masterbox.emails.orders.shipped_delivered - [Random variables]');  
    }
  }

  /** @test */
  public function email_orders_spot_delivered()
  {

    $series = App\Models\DeliverySerie::orderBy(DB::raw('RAND()'))->whereNotNull('closed')->first();
    $spot = App\Models\DeliverySpot::orderBy(DB::raw('RAND()'))->first();

    $this->sendEmail('masterbox.emails.orders.spot_delivered', [

      'first_name' => App\Models\Customer::orderBy(DB::raw('RAND()'))->first()->first_name,
      'series_date' => $series->delivery,
      'gift' => false,
      'spot_name' => $spot->name,
      'spot_name_and_infos' => $spot->emailReadableSpot(),
      'spot_schedule' => nl2br($spot->schedule), // the schedule might be on a couple of lines
    ], 'masterbox.emails.orders.spot_delivered - [$gift=false]');  

    $this->sendEmail('masterbox.emails.orders.spot_delivered', [

      'first_name' => App\Models\Customer::orderBy(DB::raw('RAND()'))->first()->first_name,
      'series_date' => $series->delivery,
      'gift' => true,
      'spot_name' => $spot->name,
      'spot_name_and_infos' => $spot->emailReadableSpot(),
      'spot_schedule' => nl2br($spot->schedule), // the schedule might be on a couple of lines
    ], 'masterbox.emails.orders.spot_delivered - [$gift=true]');  


  }


  /**
   * Abstract email send
   * @param  string $view  View of the email
   * @param  array $datas Datas given to the view
   * @return void
   */
  private function sendEmail($view, $datas, $subject)
  {
    $sent = Mail::send($view, $datas, function($m) use ($subject) {
      $m->from('testing-sender@bordeauxinbox.com', 'Bordeaux in Box');
      $m->to('testing-receipt@bordeauxinbox.com')->subject($subject);
    });

  }

}

