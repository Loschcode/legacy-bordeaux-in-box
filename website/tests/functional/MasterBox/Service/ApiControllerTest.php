<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\Customer;
use App\Models\CustomerProfile;

class MasterBox_Service_ApiControllerTest extends TestCase
{

  use DatabaseTransactions;

  /** @test */
  public function should_not_reach_post_box_question_customer_answer_when_not_logged()
  {
    $this->call('POST', 'service/api/box-question-customer-answer');
    $this->assertResponseStatus('302');
  }

}
