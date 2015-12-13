<?php namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;

use Request, Validator, Mail, Redirect, Session;

use App\Models\Contact;
use App\Models\ContactSetting;

class ContactController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Default Home Controller
  |--------------------------------------------------------------------------
  |
  | Home page system
  |
  */

  /**
   * Home page
   */
  public function getIndex()
  {
    return view('contact.index');
  }

  /**
   * Send the contact form
   */
  public function postIndex()
  {

    $rules = [

      'email' => 'required|email',
      'service' => 'required|not_in:0',
      'message' => 'required|min:5',

      ];

    $fields = Request::all();
    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $contact = new Contact;

      $contact->email = $fields['email'];
      $contact->service = $fields['service'];
      $contact->message = $fields['message'];

      // We finally send an email to confirm the account
        $data = array(

          'contact_email' => $fields['email'],
          'contact_service' => readable_contact_service($fields['service']),
          'contact_message' => $fields['message']

          );

        // Commercial stuff
        if (strpos($contact->service, 'com-') !== FALSE) {

          $email = ContactSetting::first()->com_support;

        // Technical stuff
        } elseif (strpos($contact->service, 'tech-') !== FALSE) {

          $email = ContactSetting::first()->tech_support;

        }

        $contact->recipient = $email;
        $contact->save();

      // Specific to the admisn, so we don't log it
      Mail::queue('emails.contact', $data, function($message) use ($email, $fields)
      {
          $message->from($fields['email'])->to($email)->subject('Prise de contact');
      });

      Session::flash('message', "Ton message a bien été envoyé à notre équipe, nous te ferons un retour dans les plus brefs délais.");

      return Redirect::back();

    } else {

      // We return the same page with the error and saving the input datas
      return Redirect::back()
      ->withInput()
      ->withErrors($validator);

    }

  }

}