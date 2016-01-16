<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PurchaseMasterBoxTest extends TestCase
{
    /** @test */
    public function purchase_a_box_as_guest()
    {

        $this->visit('/customer/purchase/classic')
             ->seePageIs('/connect/customer/subscribe');

        $this->post('/connect/customer/postSubscribe', [
                    'first_name' => 'Laurent',
                    'last_name' => 'Schaffner',
                    'email' => 'bonjour@laurentschaffner.com',
                    'phone' => '0640380562',
                    'password' = 'my-password',
                    'confirm_password' => 'my-password'
                    ])

        ;



    }
}
