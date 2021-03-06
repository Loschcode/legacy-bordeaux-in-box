<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Mail;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
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
        if (app()->environment() == 'production')
        {
            $email = 'laurent@bordeauxinbox.com';
            $url = request()->url();

            // TODO : rendre moins dégueulasse. Laurent 06/02/2016
            if (!strpos($url, '/traces/')) {
              if (!strpos($e, 'HttpKernel\Exception\NotFoundHttpException')) {

                $data = array('exception' => $e, 'url' => $url);

                Mail::send('shared.emails.errors', $data, function($message) use ($email)
                {
                    $message->to($email)->subject('Bordeaux in Box Error');
                });

              } 
            }

        }

        parent::report($e);
        
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
        // Hook for php errors such as "undefined variable"
        if ( ! $this->isHttpException($e) && getenv('APP_DEBUG') != 'true') {
            return response()->view('errors.500');
        }

        return parent::render($request, $e);
    }
}
