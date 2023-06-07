<?php

namespace App\Exceptions;

use App\Http\Responses\ApiResponse;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->renderable(function (OrderException $exception) {
            return ApiResponse::responseError(
                code: Response::HTTP_UNPROCESSABLE_ENTITY,
                message: $exception->getMessage()
            );
        });

        parent::register();
    }
}