<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\DeliverySerie;

class MasterBox_Guest_HomeControllerTest extends TestCase
{

  use DatabaseTransactions;
  use MailTracking;

  /** @test */
  public function should_see_homepage()
  {
    $this->visit('/')
      ->seePageIs('/');

    $this->assertViewHas('next_series');
    $this->assertViewHas('articles');
  }

  /** @test */
  public function should_be_impossible_to_try_to_order_when_no_next_series()
  {
    // Close all available series
    DeliverySerie::whereNull('closed')->update(['closed' => date('Y-m-d', time())]);

    $this->visit('/')
      ->seePageIs('/');

    $this->click('L\'offrir')->seePageIs('/');
    $this->click('La recevoir')->seePageIs('/');

  }

  /** @test */
  public function should_be_possible_to_order_when_we_have_next_series()
  {
    // Create new serie
    factory(DeliverySerie::class)->create();

    $this->visit('/')
      ->seePageIs('/');

    $this->click('L\'offrir')->seePageIs('connect/customer/choose-frequency');

    $this->visit('/');

    $this->click('La recevoir')->seePageIs('connect/customer/choose-frequency');

  }

  /** @test */
  public function should_see_legal_page()
  {
    $this->visit('legals')
      ->seePageIs('legals');

    $this->assertViewHas('legal');
  }

  /** @test */
  public function should_see_cgv_page()
  {
    $this->visit('cgv')
      ->seePageIs('cgv');

    $this->assertViewHas('cgv');
  }

  /** @test */
  public function should_see_help_page()
  {
    $this->visit('help')
      ->seePageIs('help');

    $this->assertViewHas('help');
  }



}
