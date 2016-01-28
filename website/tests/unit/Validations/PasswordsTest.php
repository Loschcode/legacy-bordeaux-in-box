<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\Customer;

class PasswordsTest extends TestCase
{

  use DatabaseTransactions;

  /** @test */
  public function should_work_as_excepted()
  {
    // Create customer
    $customer = factory(Customer::class, 'subscribed-customer')->create([
      'email' => 'jeremieges@test.com',
      'password' => bcrypt('pikachu')
    ]);

    // Connect customer
    $customer = Auth::guard('customer')->attempt(['email' => 'jeremieges@test.com', 'password' => 'pikachu']);

    $validator = Validator::make(['password' => 'pikachu'], ['password' => 'match_password']);
    $this->assertEquals(true, $validator->passes());

    $validator = Validator::make(['password' => 'wrongpassword'], ['password' => 'match_password']);
    $this->assertEquals(false, $validator->passes());

  }



}
