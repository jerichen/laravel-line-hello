<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $status_code = $exception->getStatusCode();
        if($status_code == '404'){
            $url = $request->fullUrl();
            Artisan::call('line:push', [
                'url' => $url,
                'message' => ' 404 Not Found',
            ]);
        }

        if($status_code == '500'){
            $url = $request->fullUrl();
            Artisan::call('line:push', [
                'url' => $url,
                'message' => ' 500 Internal Server Error',
            ]);
        }

        return parent::render($request, $exception);
    }
}
