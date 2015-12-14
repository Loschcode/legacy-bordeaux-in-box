<?php namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;

class RemindersController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Reminders Controller
	|--------------------------------------------------------------------------
	|
	| Forgot password stuff (from Laravel, but modified)
	|
	*/

	/**
	 * Display the password reminder view.
	 *
	 * @return Response
	 */
	public function getRemind()
	{
		$this->layout->content = view()->make('user.password.remind');
	}

	/**
	 * Handle a POST request to remind a user of their password.
	 *
	 * @return Response
	 */
	public function postRemind()
	{
		switch ($response = Password::remind(Request::only('email'), function($message) {
			$message
			->from('lolipop@bordeauxinbox.fr', 'Bordeaux in Box')
            ->subject('Réinitialisation de ton mot de passe');
		}))
		{
			case Password::INVALID_USER:
				return Redirect::back()->with('error', Lang::get($response));

			case Password::REMINDER_SENT:
				return Redirect::back()->with('status', Lang::get($response));
		}
	}

	/**
	 * Display the password reset view for the given token.
	 *
	 * @param  string  $token
	 * @return Response
	 */
	public function getReset($token = null)
	{
		if (is_null($token)) App::abort(404);

		return view('user.password.reset')->with(compact(
      'token'
    ));
	}

	/**
	 * Handle a POST request to reset a user's password.
	 *
	 * @return Response
	 */
	public function postReset()
	{
		$credentials = Request::only(
			'email', 'password', 'password_confirmation', 'token'
		);

		$rules = [

			'email' => 'required',
			'password' => 'required|min:5|confirmed',

			];

		$validator = Validator::make($credentials, $rules);

		if ($validator->passes()) {

		$response = Password::reset($credentials, function($user, $password)
		{
			$user->password = Hash::make($password);
			$user->save();
		});

		switch ($response)
		{
			case Password::INVALID_PASSWORD:
			case Password::INVALID_TOKEN:
			case Password::INVALID_USER:
				return Redirect::back()
				->with('error', Lang::get($response));

			case Password::PASSWORD_RESET:

				return Redirect::to('/user/login');

			}

		} else {

			return Redirect::back()
					->withInput()
					->withErrors($validator);

		}
	}

}