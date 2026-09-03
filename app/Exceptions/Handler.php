<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->expectsJson()) {
                return $this->handleApiException($e);
            }
        });
    }

    /**
     * Handle API exceptions.
     *
     * @param Throwable $exception
     * @return JsonResponse
     */
    private function handleApiException(Throwable $exception): JsonResponse
    {
        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, request());
        }

        if ($exception instanceof HttpException) {
            return $this->convertHttpExceptionToResponse($exception);
        }

        return $this->convertExceptionToResponse($exception);
    }

    /**
     * Convert validation exception to response.
     *
     * @param ValidationException $exception
     * @return JsonResponse
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        return response()->json([
            'success' => false,
            'message' => 'The given data was invalid.',
            'errors' => $e->errors(),
        ], 422);
    }

    /**
     * Convert HTTP exception to response.
     *
     * @param HttpException $exception
     * @return JsonResponse
     */
    private function convertHttpExceptionToResponse(HttpException $exception): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $exception->getMessage() ?: 'HTTP Error',
        ], $exception->getStatusCode());
    }

    /**
     * Convert general exception to response.
     *
     * @param Throwable $exception
     * @return JsonResponse
     */
    protected function convertExceptionToResponse(Throwable $exception)
    {
        $status = $this->isHttpException($exception) ? ($exception instanceof HttpException ? $exception->getStatusCode() : 500) : 500;
        $message = $status === 500 ? 'Server Error' : $exception->getMessage();

        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}
