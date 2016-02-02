<?php

//use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Libraries\GoogleGeocoding;
use App\Models\Customer;
use App\Models\Coordinate;

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
    $coordinates = Coordinate::where('latitude', '!=', 0)->where('longitude', '!=', 0)->limit($tests)->get();
    
    foreach ($coordinates as $coordinate) {

      $coords = GoogleGeocoding::getCoordinates($coordinate->address, $coordinate->city, $coordinate->zip);
      $this->assertEquals(TRUE, $coords['success']);

    }

  }

}
