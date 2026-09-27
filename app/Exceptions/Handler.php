<?php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\ViewErrorBag;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{

     private $login_info;
    
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [ //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation'
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
    }

    
    /**
     * Prepare a response for the given exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function prepareResponse($request, Throwable $e)
    {
        if (! $this->isHttpException($e) && config('app.debug')) {
            return $this->toIlluminateResponse($this->convertExceptionToResponse($e), $e);
        }
        
        if (! $this->isHttpException($e)) {
            $e = new HttpException(500, $e->getMessage());
        }

        if (strpos(url()->current(), env('APP_MANAGE_URL')) === 0) {
            //管理側ならログイン情報取得
            $this->login_info = getLoginInfo($request);
        }
        
        return $this->toIlluminateResponse(
            $this->renderHttpException($e), $e
            );
    }

    /**
     * Render the given HttpException.
     *
     * @param \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpExceptionInterface $e)
    {
        if (strpos(url()->current(), env('APP_MANAGE_URL')) === 0) {
            // 管理側のエラー表示
            $err_viewcode = 500;
            if (!empty($e->getStatusCode())){
                $err_viewcode = $e->getStatusCode();
            }

            $manage_view_name = 'manage.errors.' . $err_viewcode;
            return response()->view($manage_view_name,['login_info' => $this->login_info], $e->getStatusCode(), $e->getHeaders());
        } else {
            // フロント側のエラー表示（デフォルトのまま）
            $this->registerErrorViewPaths();

            if (view()->exists($view = $this->getHttpExceptionView($e))) {
                return response()->view($view, [
                    'errors' => new ViewErrorBag(),
                    'exception' => $e
                ], $e->getStatusCode(), $e->getHeaders());
            }

            return $this->convertExceptionToResponse($e);
        }
    }
}
