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

      $coordinate->address = trim(ucfirst($coordinate->address));
      $coordinate->zip = trim(ucfirst($coordinate->zip));
      $coordinate->city = trim(ucfirst($coordinate->city));

      $callback = GoogleGeocoding::getCoordinates($coordinate->address, $coordinate->city, $coordinate->zip);

      if ($callback['success']) {

        $coordinate->place_id = $callback['place_id'];
        $coordinate->latitude = $callback['latitude'];
        $coordinate->longitude = $callback['longitude'];
        $coordinate->formatted_address = $callback['formatted_address'];
        $coordinate->raw = $callback['raw'];

      } else {

        $coordinate->place_id = '';
        $coordinate->latitude = 0;
        $coordinate->longitude = 0;
        $coordinate->formatted_address = '';

      }

    });

    static::updating(function($coordinate)
    {

      $coordinate->address = trim(ucfirst($coordinate->address));
      $coordinate->zip = trim(ucfirst($coordinate->zip));
      $coordinate->city = trim(ucfirst($coordinate->city));

      $callback = GoogleGeocoding::getCoordinates($coordinate->address, $coordinate->city, $coordinate->zip);

      if ($callback['success']) {

        $coordinate->place_id = $callback['place_id'];
        $coordinate->latitude = $callback['latitude'];
        $coordinate->longitude = $callback['longitude'];
        $coordinate->formatted_address = $callback['formatted_address'];
        $coordinate->raw = $callback['raw'];
        
      } else {

        $coordinate->place_id = '';
        $coordinate->latitude = 0;
        $coordinate->longitude = 0;
        $coordinate->formatted_address = '';

      }

    });

    static::deleting(function($coordinate) {


    });

  }

  /*public function getCleanMessageAttribute()
  {

  }*/

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