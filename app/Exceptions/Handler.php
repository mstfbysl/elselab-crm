<?php

namespace App\Exceptions;

use App\Http\Traits\RespondTrait;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\ControllerDoesNotReturnResponseException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    use RespondTrait;

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

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {    
        $this->renderable(function (QueryException $e, $request) {
            if ($request->is('api/*')) {
                return $this->respondFail(__('responses.'.$e), 500);
                //return $this->respondFail(__('responses.A query error occured. It was send to system administration!'), 500);
            }
        });

        $this->renderable(function (ControllerDoesNotReturnResponseException $e, $request) {
            if ($request->is('api/*')) {
                return $this->respondFail(__('responses.'.$e), 500);
                //return $this->respondFail(__('responses.A controller error occured. It was send to system administration!'), 500);
            }
        });
    
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return $this->respondFail(__('responses.'.$e), 500);
                //return $this->respondFail(__('responses.A not found error occured. It was send to system administration!'), 404);
            }
        });
    
        $this->renderable(function (ModelNotFoundException $e, $request) {
            if ($request->is('api/*')) {
                return $this->respondFail(__('responses.'.$e), 500);
                //return $this->respondFail(__('responses.A model error occured. It was send to system administration!'), 500);
            }
        });

        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return $this->respondFail(__('responses.'.$e), 500);
                //return $this->respondFail(__('responses.Session was ended!'), 401);
            }
        });

        // $this->renderable(function (Throwable $e, $request) {
        //     if ($request->is('api/*')) {
        //         return $this->respondFail(__('responses.An unknown error occured. It was send to system administration!' . $e->getMessage()), 500);
        //     }
        // });

        // $this->renderable(function (Exception $e, $request) {
        //     if ($request->is('api/*')) {
        //         return $this->respondFail(__('responses.An unknown error occured. It was send to system administration!' . $e->getMessage()), 500);
        //     }
        // });
    }
}
