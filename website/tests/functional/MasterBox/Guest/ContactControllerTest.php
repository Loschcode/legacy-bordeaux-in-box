<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\DeliverySerie;
use App\Models\ContactSetting;

class MasterBox_Guest_ContactControllerTest extends TestCase
{

  use DatabaseTransactions;
  use MailTracking;

  /** @test */
  public function should_see_contact_form()
  {
    $this->visit('contact')
      ->seePageIs('contact');

    $this->see('<form');
  }

  /** @test */
  public function should_send_an_email_to_the_tech_admin()
  {
    $this->visit('contact')
      ->submitForm([
        'service' => 'tech-idea',
        'email' => $this->faker->email,
        'message' => $this->faker->paragraph(10)
      ]);

    $this->seeEmailWasSent()
      ->seeEmailTo(ContactSetting::first()->tech_support);
  }

  /** @test */
  public function should_send_an_email_to_the_comm_admin()
  {
    $this->visit('contact')
      ->submitForm([
        'service' => 'com-question',
        'email' => $this->faker->email,
        'message' => $this->faker->paragraph(10)
      ]);

    $this->seeEmailWasSent()
      ->seeEmailTo(ContactSetting::first()->com_support);
  }

}
