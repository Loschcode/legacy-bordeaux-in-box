<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Faker\Factory as Faker;

class PurchaseMasterBoxTest extends TestCase
{

  /** @test */
  public function try_to_purchase_a_box_as_a_guest_subscribe_then_go_to_purchase()
  {

    $faker = Faker::create();

    // Try to go first
    $this->visit('/customer/purchase/classic')
         ->seePageIs('/connect/customer/subscribe');

    // Subscribe
    $password = $faker->password;
    $this->visit('/connect/customer/subscribe')
          ->type($faker->firstName, 'first_name')
          ->type($faker->firstName, 'first_name')
          ->type($faker->lastName, 'last_name')
          ->type($faker->email, 'email')
          ->type($faker->phoneNumber, 'phone')
          ->type($password, 'password')
          ->type($password, 'password_confirmation')
          ->press("S'inscrire");

    // Was redirected to the choose box ?
    $this->seePageIs('/customer/purchase/choose-box');

  }

   /* public function test_it_display_errors_if_ad_not_correct()
    {
        $this->visit('/ajouter-annonce-baby-sitting-bordeaux')
            ->press('Poster mon annonce')
            ->see('Des erreurs sont présentes dans le formulaire.');
    }*/
    
    /*
    public function test_it_must_create_an_ad()
    {        
        $email = $this->faker->email;
        $firstname = $this->faker->firstName;
        $lastname = $this->faker->lastName;
        $phone = $this->faker->regexify('^0[567][0-9]{8}');
        $this->visit('/ajouter-annonce-baby-sitting-bordeaux')
            ->attach($this->faker->image(), 'avatar')
            ->seePageIs('/ajouter-annonce-baby-sitting-bordeaux')
            ->seeInDatabase('ads', [
                'email' => $email,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'phone' => $phone
            ])
            ->see('Annonce ajoutée');
        $this->assertEmailIsSent();
        // Fetch last message
        $message = $this->mailtrapGetLastMessage();
        // Sent to the admin
        $this->assertEmailReceiverEquals(Config::get('bsb.admin.mail'), $message);

        */

}
