<?php

namespace App\Exceptions;

use Exception;
use App\Traits\ApiResponser;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Session\TokenMismatchException;

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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
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
        
        if($exception instanceof ValidationException )
        {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }
        elseif( $exception instanceof ModelNotFoundException )
        {
            // dd($exception);
            $modelName = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("The {$modelName} could not be find by the provided id.",404);
        }
        elseif( $exception instanceof AuthenticationException )
        {
            return $this->unauthenticated($request, $exception);
        }
        elseif( $exception instanceof AuthorizationException ) {
            return $this->errorResponse($exception->getMessage(),403);
        }
        elseif( $exception instanceof NotFoundHttpException )
        {
            return $this->errorResponse("The requested resource could not be found.",404);
        }
        elseif( $exception instanceof MethodNotAllowedHttpException )
        {
            return $this->errorResponse("The resource does not support the current http method.",405);
        }
        elseif( $exception instanceof HttpException )
        {
            return $this->errorResponse($exception->getMessage(),$exception->getStatusCode());
        }
        elseif ( $exception instanceof QueryException ) 
        {
            $sqlErrorCode = $exception->errorInfo[1];
            if($sqlErrorCode == 1451)
            {
                return $this->errorResponse("The requested resource cannot be deleted until its child references exists.",409);
            }
            
        } 
        if (config('app.debug')) 
        {
            return parent::render($request, $exception);
        }
         if($exception instanceof TokenMismatchException){
        redirect()->back()->withInput($request->input());
    }
        return $this->errorResponse("Unknown Exception,Please Try again later.",500);
         // dd($exception);
    }



    protected function convertValidationExceptionToResponse($e, $request)
    {
        // $message = $e->getMessage();
        $message =$e->errors();
        if($this->isFrontend($request))
        {
            if($request->ajax())
                {
            return response()->json($message,422);
              }
              else
              {
                return redirect()->back()
                ->withInput($request->input())
                ->withErrors($message);
              }
        }
        

        
        return $this->errorResponse($message,422);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if($this->isFrontend($request))
        {
            return redirect()->guest('login');
        }
        $message = $exception->getMessage();
        return $this->errorResponse($message,401);
    }

    private function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
