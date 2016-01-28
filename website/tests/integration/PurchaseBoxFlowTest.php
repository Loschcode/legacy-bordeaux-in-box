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
use App\Models\DeliverySerie;

class PurchaseBoxFlowTest extends TestCase
{

  use DatabaseTransactions;

  public function setUp()
  {
    parent::setUp();

    // We need at least a next serie to purchase
    factory(DeliverySerie::class)->create();

  }

  /** @test */
  public function pick_gift_and_subscribe()
  {
    $this->pickGift()
      ->seePageIs('connect/customer/subscribe')
      ->subscribe(['email' => 'jeremieges@test.com'])
      ->seePageIs('customer/purchase/choose-frequency')
      ->seeInDatabase('customers', ['email' => 'jeremieges@test.com']);
  }

  /** @test */
  public function pick_classic_and_subscribe()
  {
    $this->pickClassic()
      ->seePageIs('connect/customer/subscribe')
      ->subscribe(['email' => 'jeremieges@test.com'])
      ->seePageIs('customer/purchase/choose-frequency')
      ->seeInDatabase('customers', ['email' => 'jeremieges@test.com']);
  }

  /** @test */
  public function pick_5_months_frequency_for_a_gift()
  {
    $this->pickGift()
      ->subscribe(['email' => 'jeremieges@test.com'])
      ->pickFrequency(6) // id 6 = 5 months
      ->seePageIs('customer/purchase/billing-address');
    
    // Fetch customer created
    $customer = Customer::where('email', 'jeremieges@test.com')->first();

    $this->seeInDatabase('customer_order_buildings', ['customer_id' => $customer->id, 'step' => 'billing-address']);

    $this->assertEquals(5, $customer->order_building()->first()->order_preference()->first()->frequency);
  }

  /** @test */
  public function pick_3_months_frequency_for_a_gift()
  {
    $this->pickGift()
      ->subscribe(['email' => 'jeremieges@test.com'])
      ->pickFrequency(5) // id 5 = 3 months
      ->seePageIs('customer/purchase/billing-address');
    
    // Fetch customer created
    $customer = Customer::where('email', 'jeremieges@test.com')->first();

    $this->seeInDatabase('customer_order_buildings', ['customer_id' => $customer->id, 'step' => 'billing-address']);

    $this->assertEquals(3, $customer->order_building()->first()->order_preference()->first()->frequency);

  }

  /** @test */
  public function pick_1_month_frequency_for_a_gift()
  {
    $this->pickGift()
      ->subscribe(['email' => 'jeremieges@test.com'])
      ->pickFrequency(4) // id 4 = 1 month
      ->seePageIs('customer/purchase/billing-address');
    
    // Fetch customer created
    $customer = Customer::where('email', 'jeremieges@test.com')->first();

    $this->seeInDatabase('customer_order_buildings', ['customer_id' => $customer->id, 'step' => 'billing-address']);

    $this->assertEquals(1, $customer->order_building()->first()->order_preference()->first()->frequency);

  }

  /** @test */
  public function pick_1_month_frequency_for_a_classic_box()
  {
    $this->pickClassic()
      ->subscribe(['email' => 'jeremieges@test.com'])
      ->pickFrequency(7) // id 7 = 1 month
      ->seePageIs('customer/purchase/billing-address');
    
    // Fetch customer created
    $customer = Customer::where('email', 'jeremieges@test.com')->first();

    $this->seeInDatabase('customer_order_buildings', ['customer_id' => $customer->id, 'step' => 'billing-address']);

    $this->assertEquals(1, $customer->order_building()->first()->order_preference()->first()->frequency);
  }

  /** @test */
  public function pick_unlimited_months_frequency_for_a_classic_box()
  {
    $this->pickClassic()
      ->subscribe(['email' => 'jeremieges@test.com'])
      ->pickFrequency(3) // id 3 = unlimited
      ->seePageIs('customer/purchase/billing-address');
    
    // Fetch customer created
    $customer = Customer::where('email', 'jeremieges@test.com')->first();

    $this->seeInDatabase('customer_order_buildings', ['customer_id' => $customer->id, 'step' => 'billing-address']);

    $this->assertEquals(0, $customer->order_building()->first()->order_preference()->first()->frequency);
  }

  /** @test */
  public function do_not_populate_firstname_lastname_destination_when_gift()
  {
    $this->pickGift()
      ->subscribe()
      ->pickFrequencyGift();

    $this->seeInField('destination_first_name', null);
    $this->seeInField('destination_last_name', null);

  }

  /** @test */
  public function populate_firstname_lastname_destination_when_classic()
  {
    $this->pickClassic()
      ->subscribe(['first_name' => 'jeremie', 'last_name' => 'ges'])
      ->pickFrequencyClassic();

    $this->seeInField('destination_first_name', 'jeremie');
    $this->seeInField('destination_last_name', 'ges');
  }

  /** @test */
  public function must_not_display_delivery_mode_if_not_regional_when_classic()
  {
    $this->pickClassic()
      ->subscribe()
      ->pickFrequencyClassic()
      ->fillFormDestinationBillingAndSubmit([
        'destination_zip' => '95000'
      ])
      ->seePageIs('customer/purchase/payment');
  }


  /** @test */
  public function must_not_display_delivery_mode_if_not_regional_when_gift()
  {
    $this->pickGift()
      ->subscribe()
      ->pickFrequencyGift()
      ->fillFormDestinationBillingAndSubmit([
        'destination_zip' => '95000'
      ])
      ->seePageIs('customer/purchase/payment');
  }

  /** @test */
  public function must_display_delivery_mode_if_regional_when_classic()
  {
    $this->pickClassic()
      ->subscribe()
      ->pickFrequencyClassic()
      ->fillFormDestinationBillingAndSubmit([
        'destination_zip' => '33470'
      ])
      ->seePageIs('customer/purchase/delivery-mode');
  }

  /** @test */
  public function must_display_delivery_mode_if_regional_when_gift()
  {
    $this->pickGift()
      ->subscribe()
      ->pickFrequencyGift()
      ->fillFormDestinationBillingAndSubmit([
        'destination_zip' => '33470'
      ])
      ->seePageIs('customer/purchase/delivery-mode');
  }

  /** @test */
  public function can_pay_when_gift()
  {
    $this->pickGift()
      ->subscribe(['email' => 'jeremieges@test.com'])
      ->pickFrequencyGift()
      ->fillFormDestinationBillingAndSubmit([
        'destination_zip' => '95000'
      ])
      ->pay()
      ->seePageIs('customer/purchase/box-form');

      // Fetch customer created
      $customer = Customer::where('email', 'jeremieges@test.com')->first();

      // Fetch customer stripe id
      $stripe_customer = CustomerProfile::where('customer_id', $customer->id)->first()->stripe_customer;

      // Retrieve charge
      $charge = \Stripe\Charge::all(['customer' => $stripe_customer, 'limit' => 1])['data'][0];

      // Check paid
      $this->assertEquals(true, $charge->paid);
  }

  /** @test */
  public function can_pay_when_classic()
  {
    $this->pickClassic()
      ->subscribe(['email' => 'jeremieges@test.com'])
      ->pickFrequencyClassic()
      ->fillFormDestinationBillingAndSubmit([
        'destination_zip' => '95000'
      ])
      ->pay()
      ->seePageIs('customer/purchase/box-form');

      // Fetch customer created
      $customer = Customer::where('email', 'jeremieges@test.com')->first();

      // Fetch customer stripe id
      $stripe_customer = CustomerProfile::where('customer_id', $customer->id)->first()->stripe_customer;

      // Retrieve charge
      $charge = \Stripe\Charge::all(['customer' => $stripe_customer, 'limit' => 1])['data'][0];

      // Check paid
      $this->assertEquals(true, $charge->paid);
  }

  /** @test */
  public function display_success_page_after_payment_without_customization()
  {
    $this->pickClassic()
      ->subscribe()
      ->pickFrequencyClassic()
      ->fillFormDestinationBillingAndSubmit()
      ->pay()
      ->pickNoCustomization()
      ->seePageIs('customer/purchase/confirmed');
  }

  /**
   * Generate a stripe token, and submit form payment
   * @param  array  $overrides Overrides entries for card
   * @return self
   */
  private function pay($overrides = [])
  {

    $token_id = $this->generateStripeToken($overrides)['id'];

    $this->call('POST', 'customer/purchase/payment', [
      'email' => $this->getInputOrTextAreaValue('email'),
      'stripeToken' => $token_id
    ]);

    $this->followRedirects();

    return $this;
  }

  /**
   * Alias of click to pick no customization
   * @return self
   */
  private function pickNoCustomization()
  {
    $this->click('test-no-customization');

    return $this;
  }

  /**
   * Fill form delivery mode with take away and submit it
   * @return self
   */
  private function pickTakeAway()
  {
    $this->fillFormDeliveryModeAndSubmit([
      'take_away' => 1
    ]);

    return $this;
  }

  /**
   * Fill form delivery mode with no take away and submit it
   * @return self
   */
  private function pickNoTakeAway()
  {
    $this->fillFormDeliveryModeAndSubmit([
      'take_away' => 0
    ]);

    return $this;
  }

  /**
   * Visit page gift
   * @return self
   */
  private function pickGift()
  {
    $this->visit('customer/purchase/gift');

    return $this;
  }
  /**
   * Visit page classic
   * @return self
   */
  private function pickClassic()
  {
    $this->visit('customer/purchase/classic');

    return $this;
  }

  /**
   * Visit page subscribe, fill form, submit
   * @return self
   */
  private function subscribe($datas = [])
  {
    $this->visit('connect/customer/subscribe');

    $this->fillFormSubscribeAndSubmit($datas);

    return $this;
  }

  /**
   * Visit page frequency and pick frequency id of a gift
   * @return self
   */
  private function pickFrequencyGift()
  {
    $this->pickFrequency(6);

    return $this;
  }

  /**
   * Visit page frequency and pick frequency id of a classic box
   * @return self
   */
  private function pickFrequencyClassic()
  {
    $this->pickFrequency(3);

    return $this;
  }

  /**
   * Visit page frequency and pick the frequency wanted
   * @param  int $id Frequency id wanted
   * @return self
   */
  private function pickFrequency($id)
  {
    $this->visit('customer/purchase/choose-frequency');
    $this->fillFormFrequencyAndSubmit(['delivery_price' => $id]);

    return $this;
  }

  /**
   * Fill the form delivery mode and submit it
   * @param  array  $overrides Overrides entries
   * @return void
   */
  private function fillFormDeliveryModeAndSubmit($overrides = [])
  { 
    $this->submitForm('test-commit', array_merge([
      'take_away' => 0
    ], $overrides));

    return $this;
  }

  /**
   * Fill the form destination / billing and submit it
   * @param  array  $overrides Overrides entries
   * @return self
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

    return $this;
  }

  /**
   * Fill the form frequency and submit it
   * @param  array  $overrides Overrides entries
   * @return self
   */
  private function fillFormFrequencyAndSubmit($overrides = [])
  {
    $this->submitForm('commit', array_merge([
      'delivery_price' => 6
    ], $overrides));

    return $this;

  }

  /**
   * Fill the form subscribe and submit it 
   * @param  array  $overrides Overrides entries
   * @return self
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

    return $this;

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
