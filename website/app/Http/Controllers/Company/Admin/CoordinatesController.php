<?php namespace App\Http\Controllers\Company\Admin;

use App\Http\Controllers\Company\BaseController;

use Request, Validator;

use App\Models\Coordinate;

class CoordinatesController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Admin Dashboard Controller
  |--------------------------------------------------------------------------
  |
  | The admin dashboard
  |
  */

    /**
     * Filters
     */
    public function __construct()
    {
      
        $this->beforeMethod();

    }

  /**
   * Index dashboard
   * @return void
   */
  public function getIndex()
  {

    $coordinates = Coordinate::orderBy('updated_at', 'desc')->get();

    return view('company.admin.coordinates.index', compact('coordinates'));
  }

  /**
   * Show Links for the coordinate given
   * @param  int $id The id of the coordinate
   * @return \Illuminate\Illuminate\View
   */
  public function getLinks($id)
  {
    $coordinate = Coordinate::findOrFail($id);

    return view('company.admin.coordinates.links')->with(compact(
      'coordinate'
    ));
  }

  /**
   * We a edit a coordinate
   */
  public function getEdit($id)
  {

    $coordinate = Coordinate::find($id);

    return view('company.admin.coordinates.edit')->with(compact('coordinate'));

  }

  public function postEdit()
  {

    // New article rules
    $rules = [

      'coordinate_id' => 'required|integer',
      'address' => 'required',
      'address_detail' => '',
      'city' => 'required',
      'zip' => 'required',
      'country' => 'required',

      ];


    $fields = Request::all();

    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $coordinate = Coordinate::find($fields['coordinate_id']);

      $coordinate->address = $fields['address'];

      if (!isset($fields['address_detail']))
        $coordinate->address_detail = '';
      else
        $coordinate->address_detail = $fields['address_detail'];

      $coordinate->zip = $fields['zip'];
      $coordinate->city = $fields['city'];
      $coordinate->country = $fields['country'];

      $coordinate->save();

      session()->flash('message', 'Coordonnées mises à jour');
      return redirect()->action('Company\Admin\CoordinatesController@getIndex');

    } else {

      session()->flash('error', 'Un problème est survenu lors de la mise à jour');

      // We return the same page with the error and saving the input datas
      return redirect()->back()
      ->withInput()
      ->withErrors($validator);

    }



  }


}
