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
  use MailTracking;
  
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
      ->seePageIs('customer/purchase/choose-frequency')
      ->pickFrequency(1, TRUE)
      ->seePageIs('connect/customer/subscribe')
      ->subscribe(['email' => 'jeremieges@test.com'])
      ->seePageIs('customer/purchase/billing-address')
      ->seeInDatabase('customers', ['email' => 'jeremieges@test.com']);
  }

  /** @test */
  public function pick_classic_and_subscribe()
  {
    $this->pickClassic()
      ->seePageIs('customer/purchase/choose-frequency')
      ->pickFrequency(0, FALSE)
      ->seePageIs('connect/customer/subscribe')
      ->subscribe(['email' => 'jeremieges@test.com'])
      ->seePageIs('customer/purchase/billing-address')
      ->seeInDatabase('customers', ['email' => 'jeremieges@test.com']);
  }

  /** @test */
  public function pick_5_months_frequency_for_a_gift()
  {
    $this->pickGift()
        ->seePageIs('customer/purchase/choose-frequency')
        ->pickFrequency(5, TRUE)
      ->subscribe(['email' => 'jeremieges@test.com'])
      ->seePageIs('customer/purchase/billing-address');
    
    // Fetch customer created
    $customer = Customer::where('email', 'jeremieges@test.com')->first();

    $this->seeInDatabase('customer_order_buildings', ['customer_id' => $customer->id, 'step' => 'billing-address']);

    $this->assertEquals(5, $customer->order_buildings()->first()->order_preference()->first()->frequency);

  }

  /** @test */
  public function pick_3_months_frequency_for_a_gift()
  {
    $this->pickGift()
      ->pickFrequency(3, TRUE)
      ->subscribe(['email' => 'jeremieges@test.com'])
      ->seePageIs('customer/purchase/billing-address');
    
    // Fetch customer created
    $customer = Customer::where('email', 'jeremieges@test.com')->first();

    $this->seeInDatabase('customer_order_buildings', ['customer_id' => $customer->id, 'step' => 'billing-address']);

    $this->assertEquals(3, $customer->order_buildings()->first()->order_preference()->first()->frequency);

  }

  /** @test */
  public function pick_1_month_frequency_for_a_gift()
  {
    $this->pickGift()
      ->pickFrequency(1, TRUE)
      ->subscribe(['email' => 'jeremieges@test.com'])
      ->seePageIs('customer/purchase/billing-address');
    
    // Fetch customer created
    $customer = Customer::where('email', 'jeremieges@test.com')->first();

    $this->seeInDatabase('customer_order_buildings', ['customer_id' => $customer->id, 'step' => 'billing-address']);

    $this->assertEquals(1, $customer->order_buildings()->first()->order_preference()->first()->frequency);

  }

  /** @test */
  public function pick_1_month_frequency_for_a_classic_box()
  {
    $this->pickClassic()
      ->pickFrequency(1, FALSE)
      ->subscribe(['email' => 'jeremieges@test.com'])
      ->seePageIs('customer/purchase/billing-address');
    
    // Fetch customer created
    $customer = Customer::where('email', 'jeremieges@test.com')->first();

    $this->seeInDatabase('customer_order_buildings', ['customer_id' => $customer->id, 'step' => 'billing-address']);

    $this->assertEquals(1, $customer->order_buildings()->first()->order_preference()->first()->frequency);
  }

  /** @test */
  public function pick_unlimited_months_frequency_for_a_classic_box()
  {
    $this->pickClassic()
      ->pickFrequency(0, FALSE)
      ->subscribe(['email' => 'jeremieges@test.com'])
      ->seePageIs('customer/purchase/billing-address');
    
    // Fetch customer created
    $customer = Customer::where('email', 'jeremieges@test.com')->first();

    $this->seeInDatabase('customer_order_buildings', ['customer_id' => $customer->id, 'step' => 'billing-address']);

    $this->assertEquals(0, $customer->order_buildings()->first()->order_preference()->first()->frequency);
  }

  /** @test */
  public function populate_firstname_lastname_destination_when_classic()
  {
    $this->pickClassic()
      ->pickFrequencyClassic()
      ->subscribe(['first_name' => 'jeremie', 'last_name' => 'ges']);

    $this->seeInField('destination_first_name', 'jeremie');
    $this->seeInField('destination_last_name', 'ges');
  }

  /** @test */
  public function must_not_display_delivery_mode_if_not_regional_when_classic()
  {
    $this->pickClassic()
      ->pickFrequencyClassic()
      ->subscribe()
      ->fillFormDestinationBillingAndSubmit([
        'destination_zip' => '95000'
      ])
      ->seePageIs('customer/purchase/payment');
  }


  /** @test */
  public function must_not_display_delivery_mode_if_not_regional_when_gift()
  {
    $this->pickGift()
      ->pickFrequencyGift()
      ->subscribe()
      ->fillFormDestinationBillingAndSubmit([
        'destination_zip' => '95000'
      ])
      ->seePageIs('customer/purchase/payment');
  }

  /** @test */
  public function must_display_delivery_mode_if_regional_when_classic()
  {
    $this->pickClassic()
      ->pickFrequencyClassic()
      ->subscribe()
      ->fillFormDestinationBillingAndSubmit([
        'destination_zip' => '33470'
      ])
      ->seePageIs('customer/purchase/delivery-mode');
  }

  /** @test */
  public function must_display_delivery_mode_if_regional_when_gift()
  {
    $this->pickGift()
      ->pickFrequencyGift()
      ->subscribe()
      ->fillFormDestinationBillingAndSubmit([
        'destination_zip' => '33470'
      ])
      ->seePageIs('customer/purchase/delivery-mode');
  }

  /** @test */
  public function can_pay_when_gift()
  {
    $this->pickGift()
      ->pickFrequencyGift()
      ->subscribe(['email' => 'jeremieges@test.com'])
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
      ->pickFrequencyClassic()
      ->subscribe(['email' => 'jeremieges@test.com'])
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
      ->pickFrequencyClassic()
      ->subscribe()
      ->fillFormDestinationBillingAndSubmit()
      ->pay()
      ->pickNoCustomization()
      ->seePageIs('customer/purchase/confirmed');
  }

  /** @test */
  public function should_be_possible_to_pick_a_spot_when_regional()
  {
    $this->pickClassic()
     ->pickFrequencyClassic()
      ->subscribe()
      ->fillFormDestinationBillingAndSubmit([
        'destination_zip' => '33000'
      ])
      ->pickTakeAway()
      ->pickSpot()
      ->seePageIs('customer/purchase/payment');
  }

  /** @test */
  public function should_not_be_possible_to_pick_a_spot_when_not_regional()
  {
    $this->pickClassic()
      ->pickFrequencyClassic()
      ->subscribe()
      ->fillFormDestinationBillingAndSubmit([
        'destination_zip' => '95000'
      ])
      ->seePageIs('customer/purchase/payment');
  }

  /** @test */
  public function should_be_possible_to_go_back_to_current_step_when_i_browse_outside_of_the_pipeline()
  {
    $this->pickClassic()
      ->pickFrequencyClassic()
      ->subscribe()
      ->visit('/')
      ->pickClassic()
      ->seePageIs('customer/purchase/billing-address');
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
   * Will pick a stop and submit the form
   * @return self
   */
  private function pickSpot()
  {
    $this->fillFormSpotAndSubmit();

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
    $this->pickFrequency(5, TRUE);

    return $this;
  }

  /**
   * Visit page frequency and pick frequency id of a classic box
   * @return self
   */
  private function pickFrequencyClassic()
  {
    $this->pickFrequency(1, FALSE);

    return $this;
  }

  /**
   * Visit page frequency and pick the frequency wanted
   * @param  int $id Frequency id wanted
   * @return self
   */
  private function pickFrequency($frequency, $gift)
  {
    $this->visit('customer/purchase/choose-frequency');
    $this->fillFormFrequencyAndSubmit(['delivery_price' => \App\Models\DeliveryPrice::where('frequency', '=', $frequency)->where('gift', '=', $gift)->first()->id]);

    return $this;
  }

  /**
   * Fill the form choose spot and submit it
   * @param  array  $overrides Overrides entries
   * @return self
   */
  private function fillFormSpotAndSubmit($overrides = [])
  {
    $this->submitForm(array_merge([
      'chosen_spot' => App\Models\DeliverySpot::orderByRaw('RAND()')->where('active', '=', TRUE)->limit(1)->first()->id
    ], $overrides));

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
