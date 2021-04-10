<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;

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

        // https://stackoverflow.com/a/65232257 | https://github.com/JuanDMeGon/Microservicios-con-Lumen_Gateway/blob/master/app/Exceptions/Handler.php

        $this->renderable(function (HttpException $e) {
            $code = $e->getStatusCode();
            $message = Response::$statusTexts[$code];
            return $this->errorResponse($message, $code);
        });

        $this->renderable(function (ModelNotFoundException $e) {
            $model = strtolower(class_basename($e->getModel()));
            return $this->errorResponse("Does not exist any instance of {$model} with the given id.", Response::HTTP_NOT_FOUND);
        });

        $this->renderable(function (AuthorizationException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
        });

        $this->renderable(function (AuthenticationException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        });

        $this->renderable(function (ValidationException $e) {
            $errors = $e->validator->errors()->getMessages();
            return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        });

        $this->renderable(function (UnauthorizedException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
        });

        $this->renderable(function (NotFoundHttpException $e) {
            return $this->errorResponse("Not found. You have requested access to a non-existent record or location.", Response::HTTP_NOT_FOUND);
        });

    }
}
