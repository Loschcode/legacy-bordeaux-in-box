<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Libraries\GoogleGeocoding;

class Coordinate extends Model {

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'coordinates';

  /**
   * Create / Update
   */
  public static function boot()
  {

    parent::boot();

    static::creating(function($coordinate)
    {

      $coordinate->changeFromGeocoding();

    });

    static::updating(function($coordinate)
    {

      $coordinate->changeFromGeocoding();

    });

    static::deleting(function($coordinate) {


    });

  }

  /*public function getCleanMessageAttribute()
  {

  }*/

  public function getDistanceFrom($to_coordinate) {

    $latitude_from = $this->latitude;
    $longitude_from = $this->longitude;
    $latitude_to = $to_coordinate->latitude;
    $longitude_to = $to_coordinate->longitude;

    return calculate_distance($latitude_from, $longitude_from, $latitude_to, $longitude_to);

  }

  public function changeFromGeocoding()
  {

    $this->address = trim(ucfirst(mb_strtolower($this->address)));
    $this->zip = trim(ucfirst(mb_strtolower($this->zip)));
    $this->city = trim(ucfirst(mb_strtolower($this->city)));

    $callback = GoogleGeocoding::getCoordinates($this->address, $this->city, $this->zip);

    if ($callback['success']) {

      $this->place_id = $callback['place_id'];
      $this->latitude = $callback['latitude'];
      $this->longitude = $callback['longitude'];
      $this->formatted_address = $callback['formatted_address'];
      $this->raw = $callback['raw'];

    } else {

      $this->place_id = '';
      $this->latitude = 0;
      $this->longitude = 0;
      $this->formatted_address = '';

    }

  }

  public static function getMatchingOrGenerate($address, $zip, $city)
  {

    /**
     * We check if it already exists
     */
    $coordinate = Coordinate::where('address', '=', $address)->where('zip', '=', $zip)->where('city', '=', $city)->first();

    if ($coordinate !== NULL)
      return $coordinate;

    $coordinate = new Coordinate;
    $coordinate->address = $address;
    $coordinate->zip = $zip;
    $coordinate->city = $city;
    $coordinate->country = 'France';

    $coordinate->save();

    return $coordinate;

  }

}