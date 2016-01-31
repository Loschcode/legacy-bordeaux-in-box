<?php

//use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Libraries\GoogleGeocoding;
use App\Models\Customer;

class GoogleGeocodingTest extends TestCase
{

  //use DatabaseTransactions;

  public function setUp()
  {
    parent::setUp();
  }

  /** @test */
  public function get_coordinates()
  {

    /**
     * We check some random datas from the real database
     * NOTE : This costs some Google Credits
     */
    $tests = 3;
    
    foreach (Customer::orderByRaw("RAND()")->where('address', '!=', '')->limit($tests)->get() as $customer) {

      $coords = GoogleGeocoding::getCoordinates($customer->address, $customer->city, $customer->zip);

      $this->assertEquals(TRUE, $coords['success']);

    }

  }

}
