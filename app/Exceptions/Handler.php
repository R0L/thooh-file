<?php
namespace App\Exceptions;
use App\Http\Response;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
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
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $exception
	 * @return void
	 * @throws \Exception
	 */
	public function report(Exception $exception)
	{
		parent::report($exception);
	}
	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param    $request
	 * @param  \Exception  $exception
	 * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function render($request, Exception $exception)
	{
		// 如果config配置debug为true ==>debug模式的话让laravel自行处理
		if(config('app.debug')){
			return parent::render($request, $exception);
		}
		return $this->handle($request, $exception);
	}
	/**
	 * @param            $request
	 * @param \Exception $exception
	 * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 */
	private function handle(Request $request, Exception $exception){
		if($request->is('api/*')){
			$response = [];
			$error = $this->convertExceptionToResponse($exception);
			if(config('app.debug')){
				if($error->getStatusCode() >= 500){
					if(config('app.debug')){
						$response['trace'] = $exception->getTraceAsString();
						$response['exception_code'] = $exception->getCode();
					}
				}
			}
			return Response::fail($exception->getCode(), null, $response);
		}
		else{
			return parent::render($request, $exception);
		}
	}
}