<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\Customer;

class MasterBox_Connect_CustomerControllerTest extends TestCase
{

  use DatabaseTransactions;

  /** @test */
  public function should_be_redirected_to_the_login_page_when_i_am_a_guest()
  {
    $this->visit('/connect/customer')
      ->seePageIs('/connect/customer/login');
  }

  /** @test */
  public function should_see_the_subscribe_page()
  {
    $this->visit('/connect/customer/subscribe')
      ->seePageIs('/connect/customer/subscribe');
  }

  /** @test */
  public function should_logout_me_when_i_am_connected()
  {
    $customer = factory(Customer::class)->create();

    $this->actingAs($customer, 'customer')
      ->visit('/connect/customer/logout')
      ->seePageIs('/')
      ->assertEquals(false, Auth::guard('customer')->check());
  }

  /** @test */
  public function should_subscribe_me_when_i_provide_right_informations()
  {
    
    // Make valid subscribing customer
    $form_customer = $this->fakeCustomerForm();

    // Subscribe
    $this->call('POST', 'connect/customer/subscribe', $form_customer);

    $this->assertRedirectedToAction('MasterBox\Customer\PurchaseController@getIndex');

    $this->seeInDatabase('customers', ['email' => $form_customer['email']]);

    $this->assertSessionHas('message');

    $this->seeEmailsSent(1)->seeEmailTo($form_customer['email']);

    // I'm logged.
    $this->assertEquals(true, Auth::guard('customer')->check());

  }

  /** @test */
  public function should_not_subscribe_me_when_i_provide_weak_password()
  {
    $email = $this->faker->email;
    $password = str_random(3);

    $form_customer = $this->fakeCustomerForm([
      'password' => $password,
      'password_confirmation' => $password
    ]);

    $this->call('POST', 'connect/customer/subscribe', $form_customer);

    $this->missingFromDatabase('customers', ['email' => $email]);
    $this->assertSessionHasErrors('password');
  }

  /** @test */
  public function should_not_subscribe_if_email_already_exists()
  {
    $email = $this->faker->email;

    // Add a record in the database
    factory(Customer::class)->create([
      'email' => $email
    ]);

    // Make a new customer with the same email added
    $form_customer = $this->fakeCustomerForm(['email' => $email]);

    // Subscribe
    $this->call('POST', 'connect/customer/subscribe', $form_customer);

    // Fails
    $this->assertSessionHasErrors('email');
  }

  /** @test */
  public function should_see_the_login_page()
  {
    $this->visit('connect/customer/login')
      ->seePageIs('connect/customer/login');
  }

  /** @test */
  public function should_login_me_when_i_provide_right_informations()
  {
    $email = $this->faker->email;
    $password = str_random(10);

    // Add a new record in the database
    $customer = factory(Customer::class)->create([
      'email' => $email,
      'password' => bcrypt($password)
    ]);

    // Try to connect with the same email / password
    $this->call('POST', 'connect/customer/login', [
      'email' => $email,
      'password' => $password
    ]);

    // Should works
    $this->assertEquals(true, Auth::guard('customer')->check());

  }

  /** @test */
  public function should_not_login_me_when_i_provide_wrong_password()
  { 
     $email = $this->faker->email;

     // Create new customer in the database
     $customer = factory(Customer::class)->create([
       'email' => $email,
       'password' => 'jeremie'
     ]);

     // Try to connect with wrong password
     $this->call('POST', 'connect/customer/login', [
       'email' => $email,
       'password' => 'wrongpassword'
     ]);

     // Should not connect
     $this->assertEquals(false, Auth::guard('customer')->check());

     // Wrong combination email/password
     $this->assertSessionHasErrors('email');
  }

  /**
   * Mock a raw customer form
   * @param  array  $overrides Entries to overrides
   * @return array
   */
  private function fakeCustomerForm($overrides = [])
  {
    $password = str_random(10);
    
    return array_merge([
      'email' => $this->faker->email,
      'password' => $password,
      'password_confirmation' => $password,
      'first_name' => $this->faker->firstName,
      'last_name' => $this->faker->lastName,
      'phone' => $this->faker->phoneNumber
    ], $overrides);

  }

}
