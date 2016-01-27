<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Stripe\Stripe;

class PurchaseBoxFlowTest extends TestCase
{

  use DatabaseTransactions;

  /*
  public function as_a_guest_i_buy_a_gift_box_for_5_months_and_i_deliver_it_to_a_regional_address()
  {

    // Choose type gift, must be redirected to subscribe
    $this->visit('/')
      ->click('test-pick-gift')
      ->seePageIs('/connect/customer/subscribe');

    // Fill form
    $email = $this->faker->email;
    $password = str_random(10);
    $first_name = $this->faker->firstName;
    $last_name = $this->faker->lastName;

    $this->type($first_name, 'first_name')
      ->type($last_name, 'last_name')
      ->type($email, 'email')
      ->type($this->faker->phoneNumber, 'phone')
      ->type($password, 'password')
      ->type($password, 'password_confirmation')
      ->type(csrf_token(), '_token');

    // Submit form
    $this->press('test-subscribe');

    // New customer created
    $this->seeInDatabase('customers', ['email' => $email]);

    // Redirect to the step choose frequency of the pipeline purchase
    $this->seePageIs('/customer/purchase/choose-frequency');

    // Pick frequency 5 months
    // id 6 = 5 months
    $this->select(6, 'delivery_price');

    // Submit form
    $this->press('commit');

    // Step billing address 
    $this->seePageIs('/customer/purchase/billing-address');

    // Check if I can come back to the step choose frequency
    $this->click('test-step-choose-frequency')
      ->seePageIs('/customer/purchase/choose-frequency');

    // Go back to the current step
    $this->visit('/customer/purchase/billing-address')
      ->seePageIs('/customer/purchase/billing-address');

    // Check if destination first name and last name 
    // are not populated (it's a gift case)
    $this->seeInField('destination_first_name', null);
    $this->seeInField('destination_last_name', null);

    // Fill destination
    $this->type('Gujan-Mestras', 'destination_city');
    $this->type('33470', 'destination_zip');
    $this->type('23 allée jacques bossuet', 'destination_address');
    $this->type($this->faker->firstName, 'destination_first_name');
    $this->type($this->faker->lastName, 'destination_last_name');

    // Check if fake inputs billing first name and last name
    // are already populated
    $this->seeInField('fake_billing_first_name', $first_name);
    $this->seeInField('fake_billing_last_name', $last_name);

    // Check if hidden inputs billing first name and last name
    // are already populated
    $this->seeInField('billing_first_name', $first_name);
    $this->seeInField('billing_last_name', $last_name);

    // Fill form billing
    $this->type($this->faker->city, 'billing_city');
    $this->type($this->faker->postcode, 'billing_zip');
    $this->type($this->faker->address, 'billing_address');
    $this->type(csrf_token(), '_token');

    $this->press('test-commit');

    // Can choose by spot or delivery
    $this->seePageIs('/customer/purchase/delivery-mode');

    // I can go to the previous step
    $this->click('test-step-billing-address')
      ->seePageIs('/customer/purchase/billing-address');

    // Everything must be populated, so i can submit it
    // again and go back to the current step
    $this->press('test-commit')
      ->seePageIs('/customer/purchase/delivery-mode');

    // I can go back to the very first step
    $this->click('test-step-choose-frequency')
      ->seePageIs('/customer/purchase/choose-frequency');

    // The frequency is already populated 
    // id 6 = 5 months
    $this->seeIsSelected('delivery_price', 6);

    // Go back to the current step
    $this->press('commit');
    $this->press('test-commit');

    // Choose no take away
    $this->select(0, 'take_away');

    // Submit form
    $this->press('test-commit')
      ->seePageIs('customer/purchase/payment');

    // Create stripe token
    $request = $this->generateStripeToken();

    $token = $request['id'];

    // Call next step
    $this->call('POST', '/customer/purchase/payment', [
      '_token' => csrf_token(),
      'email' => $this->getInputOrTextAreaValue('email'),
      'stripeToken' => $token
    ]);

    $this->followRedirects();

    $this->seePageIs('/customer/purchase/box-form');

  }
  */

  /**
   * Generate a card token
   * @return array
   */
  private function generateStripeToken($overrides = [])
  {

    \Stripe\Stripe::setApiKey(getenv('STRIPE_API_KEY'));

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
