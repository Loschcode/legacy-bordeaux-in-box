<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\Customer;

/**
 * NOT ENOUGH. IT'S RIDICULOUS. WE NEED A SHIT LOAD OF TESTS HERE.
 */
class MasterBox_Customer_PurchaseControllerTest extends TestCase
{

  use DatabaseTransactions;
  use MailTracking;

  /** @test */
  public function should_be_redirected_to_the_subscribe_page_when_i_am_a_guest_an_i_try_to_order_a_gift_box()
  {
    $this->visit('customer/purchase/gift')
      ->seePageIs('customer/purchase/choose-frequency');
  }

  /** @test */
  public function should_be_redirected_to_the_subscribe_page_when_i_am_a_guest_an_i_try_to_order_a_classicbox()
  {
    $this->visit('customer/purchase/classic')
      ->seePageIs('customer/purchase/choose-frequency');
  }

  public function should_be_redirected_to_the_subscribe_page_when_i_am_choosing_a_frequency()
  {

    $this->visit('customer/purchase/choose-frequency')
         ->fillFormFrequencyAndSubmit(['delivery_price' => 6])
         ->seePageIs('connect/customer/subscribe');
  }


}
