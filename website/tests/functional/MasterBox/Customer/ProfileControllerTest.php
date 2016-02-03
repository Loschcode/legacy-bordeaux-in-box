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

  /** @test */
  public function should_see_index_when_connected()
  {
    $this->createAndConnect();

    $this->visit('customer/profile/index')
      ->seePageIs('customer/profile/index');

  }

  /** @test */
  public function should_be_redirect_to_login_page_when_not_connected_when_reach_contact()
  {
    $this->visit('customer/profile/contact')
      ->seePageIs('connect/customer/login');
  }

  /** @test */
  public function should_get_contact_when_connected()
  {
    $customer = $this->createAndConnect();

    $this->visit('customer/profile/contact')
      ->seePageIs('customer/profile/contact');

    $this->assertViewHas('customer');
    $this->assertViewHas('active_menu');

    $this->seeInField('email', Auth::guard('customer')->user()->email);

  }

  /** @test */
  public function should_send_an_email_and_redirect_to_the_same_page_when_submit_contact()
  {
    $this->createAndConnect();

    $this->visit('customer/profile/contact');

    $this->submitForm([
        'message' => $this->faker->paragraph(10),
        'service' => 'com-question'
      ]);

    $this->seePageIs('customer/profile/contact');
    $this->seeEmailWasSent();

  }


  /**
   * Create a customer and connect him
   * @param  string $email    Email
   * @param  string $password Passowrd
   * @return self
   */
  private function createAndConnect($email='', $password='')
  {

    if (empty($password)) $password = str_random(10);
    if (empty($email)) $email = $this->faker->email;

    $customer = factory(Customer::class)->create([
      'password' => bcrypt($password),
      'email' => $email
    ]);

    // Log in user
    Auth::guard('customer')->attempt(['email' => $email, 'password' => $password]);

    return $this;

  }



}
