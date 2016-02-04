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
      ->seePageIs('connect/customer/subscribe');
  }

  /** @test */
  public function should_be_redirected_to_the_subscribe_page_when_i_am_a_guest_an_i_try_to_order_a_classicbox()
  {
    $this->visit('customer/purchase/classic')
      ->seePageIs('connect/customer/subscribe');
  }

  public function should_not_see_frequency_page_when_i_am_connected_but_i_did_not_choose_the_type_of_order()
  {
    // Create customer
    $customer = factory(Customer::class)->create();

    $this->actingAs($customer, 'customer')
      ->visit('customer/purchase/choose-frequency')
      ->seePageIs('/');
  }

}
