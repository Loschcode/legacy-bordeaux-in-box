<?php namespace App\Http\Controllers\MasterBox\Admin;

use App\Http\Controllers\MasterBox\BaseController;

use App\Models\Contact;
use App\Models\Order;
use App\Models\EmailTrace;
use App\Models\ContactSetting;
use App\Models\CustomerProfileNote;

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

  }
    

  /**
   * Get the listing page of the blog
   * @return void
   */
	public function getIndex()
	{

		$contacts = Contact::orderBy('created_at', 'DESC')->limit(500)->get();

		return view('masterbox.admin.logs.index')->with(compact(
      'contacts'
    ));
	}

  /**
   * Fetch and display message contact id
   * @return \Illuminate\Illuminate\View
   */
  public function getContact($contact_id)
  {
    $contact = Contact::findOrFail($contact_id);

    return view('masterbox.admin.logs.contact')->with(compact('contact'));
  }


  public function getEmailTraces()
  {
    $email_traces = EmailTrace::orderBy('created_at', 'DESC')->limit(500)->get();

    return view('masterbox.admin.logs.email_traces')->with(compact('email_traces'));
  }

  public function getProfileNotes()
  {
    $profile_notes = CustomerProfileNote::orderBy('created_at', 'DESC')->limit(500)->get();
    
    return view('masterbox.admin.logs.profile_notes')->with(compact('profile_notes'));
  }


  public function getEmailTrace($id)
  {

    $email_trace = EmailTrace::findOrFail($id);

    return view('masterbox.admin.logs.email_trace')->with(compact(
      'email_trace'
    ));
  }

  public function getEditSettings()
  {
    $contact_setting = ContactSetting::first();

    return view('masterbox.admin.logs.edit_settings')->with(compact('contact_setting'));

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

    $email_trace = EmailTrace::findOrFail($id);

    $email_trace->delete();

    session()->flash('message', "Cette trace a été définitivement supprimée");
    return redirect()->back();


  }

	public function postEditSettings()
	{

		// New article rules
		$rules = [

			'tech_support' => 'required|email',
			'com_support' => 'required|email',

			];


		$fields = request()->all();

		$validator = validator()->make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

			$contact_setting = ContactSetting::first();

			$contact_setting->com_support = $fields['com_support'];
			$contact_setting->tech_support = $fields['tech_support'];
		
			$contact_setting->save();

			session()->flash('message', "La configuration a bien été mise à jour");
			return redirect()->action('MasterBox\Admin\LogsController@getEditSettings')
			->withInput();

		} else {

			// We return the same page with the error and saving the input datas
			return redirect()->back()
			->withInput()
			->withErrors($validator);

		}




	}

}