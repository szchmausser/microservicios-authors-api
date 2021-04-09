<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // https://stackoverflow.com/a/65232257

        $this->renderable(function (AuthenticationException $e) {
            return response()->json([
                'error' => 'Authentication is required. To process this, you must first authenticate to use your personal token in every request. Try to clear the browser cache and login again', ], 401);
        });

        $this->renderable(function (UnauthorizedException $e) {
            return response()->json([
                'error' => 'The requested action could not be performed because you do not have the right permissions.', ], 403);
        });

        $this->renderable(function (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'The request entry for the model '.str_replace('App\\', '', $e->getModel()).' not found', ], 404);
        });

        $this->renderable(function (NotFoundHttpException $e) {
            return response()->json([
                'error' => 'Not found. You have requested a non-existent resource.', ], 404);
        });

    }
}
