<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
    $customer = factory(App\Models\Customer::class, 'basic-customer')->create();

    $this->actingAs($customer, 'customer')
      ->visit('/connect/customer/logout')
      ->seePageIs('/')
      ->assertEquals(false, Auth::guard('customer')->check());
  }

  /** @test */
  public function should_subscribe_me_when_i_provide_right_informations()
  {
    $email = $this->faker->email;
    $password = str_random(10);

    $this->postSubscribe([
      'email' => $email,
      'password' => $password,
      'password_confirmation' => $password
    ]);

    $this->seeInDatabase('customers', ['email' => $email])
      ->assertRedirectedToAction('MasterBox\Customer\PurchaseController@getIndex');

    $this->assertSessionHas('message');

    // I'm logged.
    $this->assertEquals(true, Auth::guard('customer')->check());

  }

  /** @test */
  public function should_not_subscribe_me_when_i_provide_weak_password()
  {
    $email = $this->faker->email;
    $password = str_random(3);

    $this->postSubscribe([
      'email' => $email,
      'password' => $password,
      'password_confirmation' => $password
    ]);

    $this->missingFromDatabase('customers', ['email' => $email]);
    $this->assertSessionHasErrors('password');
  }

  /** @test */
  public function should_not_subscribe_if_email_already_exists()
  {
    $email = $this->faker->email;

    $customer = factory(App\Models\Customer::class, 'basic-customer')->create([
      'email' => $email
    ]);

    $this->postSubscribe(['email' => $email]);

    $this->assertSessionHasErrors('email');
  }

  /** @test */
  public function should_see_the_login_page()
  {
    $this->visit('/connect/customer/login')
      ->seePageIs('/connect/customer/login');
  }

  /** @test */
  public function should_login_me_when_i_provide_right_informations()
  {
    $email = $this->faker->email;
    $password = str_random(10);

    $customer = factory(App\Models\Customer::class, 'basic-customer')->create([
      'email' => $email,
      'password' => bcrypt($password)
    ]);


    $this->call('POST', '/connect/customer/login', [
      'email' => $email,
      'password' => $password
    ]);

    $this->assertEquals(true, Auth::guard('customer')->check());

  }

  /** @test */
  public function should_not_login_me_when_i_provide_wrong_password()
  { 
     $email = $this->faker->email;

     $customer = factory(App\Models\Customer::class, 'basic-customer')->create([
       'email' => $email,
       'password' => 'jeremie'
     ]);


     $this->call('POST', '/connect/customer/login', [
       'email' => $email,
       'password' => 'wrongpassword'
     ]);

     $this->assertEquals(false, Auth::guard('customer')->check());
     $this->assertSessionHasErrors('email'); // Wrong combination email/password
  }

  /** @test */
  public function should_redirect_me_to_the_pipeline_after_the_login_page_if_i_was_buying_a_gift()
  {
    $email = $this->faker->email;
    $password = str_random(10);

    $customer = factory(App\Models\Customer::class, 'basic-customer')->create([
      'email' => $email,
      'password' => bcrypt($password)
    ]);

    $this->withSession(['isOrdering' => true, 'isGift' => true]);

    $this->call('POST', '/connect/customer/login', [
      'email' => $email,
      'password' => $password
    ]);

    $this->assertRedirectedToAction('MasterBox\Customer\PurchaseController@getGift');

  }

  /** @test */
  public function should_redirect_me_to_the_pipeline_after_the_login_page_if_i_was_buying_a_classic_box()
  {
    $email = $this->faker->email;
    $password = str_random(10);

    $customer = factory(App\Models\Customer::class, 'basic-customer')->create([
      'email' => $email,
      'password' => bcrypt($password)
    ]);

    $this->withSession(['isOrdering' => true, 'isGift' => false]);

    $this->call('POST', '/connect/customer/login', [
      'email' => $email,
      'password' => $password
    ]);

    $this->assertRedirectedToAction('MasterBox\Customer\PurchaseController@getClassic');

  }

  /**
   * Perform a POST request on subscribe
   * @param  array  $datas Datas to merge with the defaults provided
   * @return void
   */
  private function postSubscribe($datas = [])
  {
    $password = str_random(10);

    $default = array_merge([
      'first_name' => $this->faker->firstName,
      'last_name' => $this->faker->lastName,
      'email' => $this->faker->email,
      'password' => $password,
      'password_confirmation' => $password,
      'phone' => $this->faker->phoneNumber
    ], $datas);

    $this->call('POST', '/connect/customer/subscribe', $default);
  }

}
