<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Stripe\Stripe;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\CustomerProfile;
use App\Models\DeliveryPrice;
use App\Models\DeliverySetting;

class PurchaseBoxFlowTest extends TestCase
{

  use DatabaseTransactions;

  /** @test */
  public function as_a_guest_i_buy_a_gift_box_for_5_months_and_i_deliver_it_to_a_regional_address_without_customization()
  {

    // Choose type gift
    $this->pickGift();
    
    // Should see customer subscribe
    $this->seePageIs('connect/customer/subscribe');

    // Subscribe
    $email = $this->faker->email;
    $this->subscribe(['email' => $email]);

    // Should create a customer
    $this->seeInDatabase('customers', ['email' => $email]);

    // Fetch customer created
    $customer = Customer::where('email', $email)->first();

    // Should sent an email to the user
    $this->seeEmailsSent(1)->seeEmailTo($customer->email);

    // Should land on choose frequency
    $this->seePageIs('customer/purchase/choose-frequency');

    // Pick frequency 5 months
    // (id = 6)
    $this->pickFrequency(6);

    // Should land on billing address step
    $this->seePageIs('customer/purchase/billing-address');

     // Check if destination first name and last name 
    // are not populated (it's a gift case)
    $this->seeNotPopulatedDestinationFirstNameLastName();

    // Check if hidden inputs billing first name and last name
    // are already populated
    $this->seeInField('billing_first_name', $customer->first_name);
    $this->seeInField('billing_last_name', $customer->last_name);

    // Fill form destination
    // with a regionnal address
    $this->fillFormDestinationBillingAndSubmit([
      'destination_city' => 'Bordeaux',
      'destination_zip' => '33000',
      'destination_address' => '18 porte soleil',
      'billing_first_name' => $customer->first_name,
      'billing_last_name' => $customer->last_name
    ]);

    // Sould land on the page to choose by spot or delivery because it's a regionnal address
    $this->seePageIs('customer/purchase/delivery-mode');

    $this->pickNoTakeAway();

    // Should land on the page to fill my credit card
    $this->seePageIs('customer/purchase/payment');

    // Create stripe token (card)
    $token = $this->generateStripeToken();

    // Submit the payment form
    $this->call('POST', 'customer/purchase/payment', [
      'email' => $this->getInputOrTextAreaValue('email'),
      'stripeToken' => $token
    ]);

    $this->followRedirects();

    $this->seePageIs('customer/purchase/box-form');

    $this->pickNoCustomization();

    $this->seePageIs('customer/purchase/confirmed');

    // Fetch customer stripe id
    $stripe_customer = CustomerProfile::where('customer_id', $customer->id)->first()->stripe_customer;

    // Fetch total amount for that frequency (id 6 = 5 months)
    $total_amount = number_format(DeliveryPrice::find(6)->unity_price + (DeliverySetting::first()->regional_delivery_fees) * 5, 2);

    // Retrieve charge
    $charge = \Stripe\Charge::all(['customer' => $stripe_customer, 'limit' => 1])['data'][0];

    $this->assertEquals(true, $charge->paid);
    $this->assertEquals($total_amount, number_format($charge->amount/100, 2));

  }

  private function pickNoCustomization()
  {
    $this->click('test-no-customization');
  }

  private function pickTakeAway()
  {
    $this->fillFormDeliveryMode([
      'take_away' => 1
    ]);

    return $this;
  }

  private function pickNoTakeAway()
  {
    $this->fillFormDeliveryMode([
      'take_away' => 0
    ]);

    return $this;
  }

  private function pickGift()
  {
    $this->visit('customer/purchase/gift');

    return $this;
  }

  private function pickClassic()
  {
    $this->visit('customer/purchase/classic');

    return $this;
  }

  private function subscribe($datas)
  {
    $this->visit('connect/customer/subscribe');

    $this->fillFormSubscribeAndSubmit($datas);

    return $this;
  }

  private function pickFrequency($id)
  {
    $this->visit('customer/purchase/choose-frequency');
    $this->fillFormFrequencyAndSubmit(['delivery_price' => $id]);

    return $this;
  }

  /**
   * Check if destination_first_name and destination_last_name is not
   * populate
   * @return void
   */
  private function seeNotPopulatedDestinationFirstNameLastName()
  {
    $this->seeInField('destination_first_name', null);
    $this->seeInField('destination_last_name', null);
  }

  /**
   * Fill the form delivery mode and submit it
   * @param  array  $overrides Overrides entries
   * @return void
   */
  private function fillFormDeliveryMode($overrides = [])
  { 
    $this->submitForm('test-commit', array_merge([
      'take_away' => 0
    ], $overrides));
  }

  /**
   * Fill the form destination / billing and submit it
   * @param  array  $overrides Overrides entries
   * @return void
   */
  private function fillFormDestinationBillingAndSubmit($overrides = [])
  {

    $this->submitForm('test-commit', array_merge([

      'destination_first_name' => $this->faker->firstName,
      'destination_last_name' => $this->faker->lastName,
      'destination_address' => $this->faker->address,
      'destination_city' => $this->faker->city,
      'destination_zip' => $this->faker->postcode,

      'billing_first_name' => $this->faker->firstName,
      'billing_last_name' => $this->faker->lastName,
      'billing_address' => $this->faker->address,
      'billing_city' => $this->faker->city,
      'billing_zip' => $this->faker->postCode,

    ], $overrides));

  }

  /**
   * Fill the form frequency and submit it
   * @param  array  $overrides Overrides entries
   * @return void
   */
  private function fillFormFrequencyAndSubmit($overrides = [])
  {
    $this->submitForm('commit', array_merge([
      'delivery_price' => 6
    ], $overrides));

  }

  /**
   * Fill the form subscribe and submit it 
   * @param  array  $overrides Overrides entries
   * @return void
   */
  private function fillFormSubscribeAndSubmit($overrides = [])
  {

    $password = str_random(10);

    $this->submitForm('test-subscribe', array_merge([
      'first_name' => $this->faker->firstName,
      'last_name' => $this->faker->lastName,
      'email' => $this->faker->email,
      'phone' => $this->faker->phoneNumber,
      'password' => $password,
      'password_confirmation' => $password
    ], $overrides));

  }

  /**
   * Generate a card token
   * @param  array Overrides entries
   * @return array
   */
  private function generateStripeToken($overrides = [])
  {
    $token = \Stripe\Token::create([
      'card' => array_merge([
        "number" => "4242424242424242",
        "exp_month" => 1,
        "exp_year" => 2027,
        "cvc" => "314"
      ], $overrides)
    ]);

    return $token;
  }

}
