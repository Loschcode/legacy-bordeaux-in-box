<?php namespace App\Http\Controllers\MasterBox\Admin;

use App\Http\Controllers\MasterBox\BaseController;

use App\Models\Contact;
use App\Models\Order;
use App\Models\EmailTrace;
use App\Models\ContactSetting;
use App\Models\UserProfileNote;

class LogsController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Admin Logs & Configuration Controller
	|--------------------------------------------------------------------------
	|
	| - Configure stuff
  | - Checkout logs
	|
	*/

    /**
     * Filters
     */
    public function __construct()
    {
    	
    	$this->beforeMethod();
      $this->middleware('is.admin');

    }
    

    /**
     * Get the listing page of the blog
     * @return void
     */
	public function getIndex()
	{

		$contacts = Contact::orderBy('created_at', 'DESC')->get();

    $all_orders = Order::orderBy('created_at', 'DESC')->get();

    $email_traces = EmailTrace::orderBy('created_at', 'DESC')->get();

		$contact_setting = ContactSetting::first();

    $profile_notes = UserProfileNote::orderBy('created_at', 'DESC')->get();

		return view('master-box.admin.logs.index')->with(compact(
      'contacts',
      'all_orders',
      'email_traces',
      'contact_setting',
      'profile_notes'
    ));

	}

  public function getMore($id)
  {

    $email_trace = EmailTrace::find($id);

    return view('master-box.admin.logs.more')->with(compact(
      'email_trace'
    ));
  }

	/**
	 * We remove the contact request
	 */
	public function getDelete($id)
	{

		$contact = Contact::find($id);

		if ($contact !== NULL)
		{

			$contact->delete();

			session()->flash('message', "Cette prise de contact a été archivée");
			return redirect()->back();


		}

	}

  /**
   * We remove the email trace
   */
  public function getDeleteEmailTrace($id)
  {

    $email_trace = EmailTrace::find($id);

    if ($email_trace !== NULL)
    {

      $email_trace->delete();

      session()->flash('message', "Cette trace a été définitivement supprimée");
      return redirect()->to(URL::previous().'#emails-traces');


    }

  }

	public function postEditSettings()
	{

		// New article rules
		$rules = [

			'tech_support' => 'required|email',
			'com_support' => 'required|email',

			];


		$fields = Request::all();

		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

			$contact_setting = ContactSetting::first();

			$contact_setting->com_support = $fields['com_support'];
			$contact_setting->tech_support = $fields['tech_support'];
		
			$contact_setting->save();

			session()->flash('message', "La configuration a bien été mise à jour");
			return redirect()->to('admin/logs#config')
			->withInput();

		} else {

			// We return the same page with the error and saving the input datas
			return redirect()->to('admin/logs#config')
			->withInput()
			->withErrors($validator);

		}




	}

}