<?php namespace App\Http\Controllers;

class AdminLogsController extends BaseController {

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
        $this->beforeFilter('isAdmin');

    }
    
	/**
     * The layout that should be used for responses.
     */
    protected $layout = 'layouts.admin';

    /**
     * Get the listing page of the blog
     * @return void
     */
	public function getIndex()
	{

		$contacts = Contact::orderBy('created_at', 'DESC')->get();
		View::share('contacts', $contacts);

    $all_orders = Order::orderBy('created_at', 'DESC')->get();
    View::share('all_orders', $all_orders);

    $email_traces = EmailTrace::orderBy('created_at', 'DESC')->get();
    View::share('email_traces', $email_traces);

		$contact_setting = ContactSetting::first();
		View::share('contact_setting', $contact_setting);

    $profile_notes = UserProfileNote::orderBy('created_at', 'DESC')->get();
    View::share('profile_notes', $profile_notes);

		$this->layout->content = View::make('admin.logs.index');

	}

  public function getMore($id)
  {

    $email_trace = EmailTrace::find($id);

    return View::make('admin.logs.more')->with(compact('email_trace'));
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

			Session::flash('message', "Cette prise de contact a été archivée");
			return Redirect::back();


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

      Session::flash('message', "Cette trace a été définitivement supprimée");
      return Redirect::to(URL::previous().'#emails-traces');


    }

  }

	public function postEditSettings()
	{

		// New article rules
		$rules = [

			'tech_support' => 'required|email',
			'com_support' => 'required|email',

			];


		$fields = Input::all();

		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

			$contact_setting = ContactSetting::first();

			$contact_setting->com_support = $fields['com_support'];
			$contact_setting->tech_support = $fields['tech_support'];
		
			$contact_setting->save();

			Session::flash('message', "La configuration a bien été mise à jour");
			return Redirect::to('admin/logs#config')
			->withInput();

		} else {

			// We return the same page with the error and saving the input datas
			return Redirect::to('admin/logs#config')
			->withInput()
			->withErrors($validator);

		}




	}

}