<?php namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Mail;
use Log;
use Response;

class Handler extends ExceptionHandler {

  /**
   * A list of the exception types that should not be reported.
   *
   * @var array
   */
  protected $dontReport = [
    'Symfony\Component\HttpKernel\Exception\HttpException'
  ];

  /**
   * Report or log an exception.
   *
   * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
   *
   * @param  \Exception  $e
   * @return void
   */
  public function report(Exception $e)
  {
    return parent::report($e);
  }

  /**
   * Render an exception into an HTTP response.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Exception  $e
   * @return \Illuminate\Http\Response
   */
  public function render($request, Exception $e)
  {

      /**
       * Send an email to the current website administrator
       */
      //if (app()->environment('production')) {
      
        $email = 'laurent@bordeauxinbox.com';
        $data = array('exception' => $e);

        Mail::send('emails.errors', $data, function($message) use ($email)
        {
            $message->to($email)->subject('Bordeaux in Box Error');
        });

        //Log::info('Error Email sent to ' . $email);
        return Response::view('standalone.error', [], 500);

      //}
      /**
       * End email send
       */
      
    if ($this->isHttpException($e))
    {
      return $this->renderHttpException($e);
    }
    else
    {
      return parent::render($request, $e);
    }
  }

}
