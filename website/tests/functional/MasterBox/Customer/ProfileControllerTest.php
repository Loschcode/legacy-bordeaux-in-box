<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\Customer;

class MasterBox_Connect_ProfileControllerTest extends TestCase
{

  use DatabaseTransactions;

  /** @test */
  public function should_be_redirect_to_login_page_when_not_connected_when_reach_index()
  {
    $this->visit('customer/profile/index')
      ->seePageIs('connect/customer/login');
  }

  public function should_see_index_when_connected()
  {
    $customer = factory(Customer::class, 'subsribed-customer')->create();

    // Log in user
    Auth::guard('customer')->attempt(['email' => $customer->email, 'password' => $customer->password]);

    $this->visit('customer/profile/index')
      ->seePageIs('customer/profile/index');

  }

}
