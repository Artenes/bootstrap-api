<?php

namespace App\Exceptions;

use App\Http\ApiResponse;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {

        parent::report($exception);

    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {

        $exception = $this->prepareException($exception);

        if ($exception instanceof HttpResponseException) {
            return $this->formatResponseException($exception);
        } elseif ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        } elseif ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        return $this->prepareResponse($request, $exception);

    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Auth\AuthenticationException $exception
     * @return ApiResponse
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {

        return ApiResponse::unauthorized(trans('messages.invalid_credentials'));

    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException $e
     * @param  \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {

        if ($e->response)
            return $e->response;

        $errors = $e->validator->errors()->getMessages();

        return ApiResponse::failedValidation(trans('messages.validation_error'), $errors);

    }

    /**
     * Map the given exception into an api json response.
     *
     * @param  \Symfony\Component\HttpFoundation\Response $response
     * @param  \Exception $e
     * @return ApiResponse
     */
    protected function toIlluminateResponse($response, Exception $e)
    {

        return $this->toApiResponse($response, $e);

    }

    /**
     * Format a HttpResponseException.
     *
     * @param HttpResponseException $exception
     * @return ApiResponse
     */
    protected function formatResponseException(HttpResponseException $exception)
    {

        return $this->toApiResponse($exception->getResponse(), $exception);

    }

    /**
     * Converts the given response to an Api response.
     *
     * @param $response
     * @param Exception $exception
     * @return ApiResponse
     */
    protected function toApiResponse($response, Exception $exception)
    {

        $isDebug = config('app.debug');

        $message = $isDebug ? $exception->getMessage() : trans('messages.error_while_processing_your_request');

        $envelopes = $isDebug ? ['trace' => $exception->getTrace()] : [];

        return ApiResponse::make(
            $message, [], [],
            $response->getStatusCode(),
            $response->headers->all(),
            $envelopes
        );

    }

}