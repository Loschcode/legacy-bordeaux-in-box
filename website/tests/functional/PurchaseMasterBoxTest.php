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

    //$faker = Faker::create();

    // Try to go first
    $this->visit('/customer/purchase/classic')
         ->seePageIs('/connect/customer/subscribe');

    // Subscribe
    $password = $this->faker->password;
    $this->visit('/connect/customer/subscribe')
          ->type($this->faker->firstName, 'first_name')
          ->type($this->faker->lastName, 'last_name')
          ->type($this->faker->email, 'email')
          ->type($this->faker->phoneNumber, 'phone')
          ->type($password, 'password')
          ->type($password, 'password_confirmation')
          ->press("M'inscrire");

    // Was redirected to the choose box ?
    $this->seePageIs('/customer/purchase/choose-frequency');

  }

  /** @test */
  public function purchase_a_one_shot_box_with_regional_address()
  {

    // We subscribe first
    $this->try_to_purchase_a_box_as_a_guest_subscribe_then_go_to_purchase();

    // We check we are effectively there
    $this->visit('/customer/purchase/classic')
          ->seePageIs('/customer/purchase/choose-frequency');

    // Choose frequency (7 = 26.90€ offer)
    // --
    $this->select(7, 'delivery_price');
    $this->press("Valider")
         ->seePageIs('/customer/purchase/billing-address');

    // Billing Address
    // --
    // We fill only the address, zip, city since the other data are supposed
    // To be auto populated from the subscription
    $this->type($this->faker->city, 'billing_city')
         ->type('33000', 'billing_zip') // We use regional only
         ->type($this->faker->address, 'billing_address')
         ->click("Copier les informations de livraison")
         ->press("Valider");

         dd($this);
         /*
         ->seePageIs('/customer/purchase/delivery-mode');*/


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
