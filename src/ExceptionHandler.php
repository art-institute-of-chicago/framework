<?php

namespace Aic\Hub\Foundation;

use Aic\Hub\Foundation\Exceptions\AbstractException;
use Aic\Hub\Foundation\Exceptions\UnauthorizedException;
use Illuminate\Auth\AuthenticationException;
use Throwable;

use Illuminate\Foundation\Exceptions\Handler;

class ExceptionHandler extends Handler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Symfony\Component\Console\Exception\CommandNotFoundException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @return void
     */
    public function report(Throwable $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response. We assume that the request always wants JSON.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $e)
    {
        $this->unsetSensitiveData();

        $is_detailed = $e instanceof AbstractException;

        // Laravel's backtrace page is too readable to forgo
        // Show it for undetailed exceptions on dev env
        if (config('app.debug') && !$is_detailed) {
            return parent::render($request, $e);
        }

        // Default status code to 500
        $status = $this->isHttpException($e) ? $e->getStatusCode() : 500;

        // Define the default response
        $response = [
            'status' => $status,
            'error' => 'Sorry, something went wrong.',
            'detail' => 'An unrecognized exception was thrown. Our developers have been alerted to the situation.',
        ];

        // For our custom exceptions, output the messages
        if ($is_detailed) {
            $response['error'] = $e->getMessage();
            $response['detail'] = $e->getDetail();
        }

        // Return a JSON response with the response array and status code
        return response()->json($response, $status);
    }

    /**
     * Avoid redirect to potentially non-existent `login` route, and ensure that the returned
     * JSON fits our API's error structure. Using `ForceAcceptJson` middleware would also fix
     * the redirect issue, but not the JSON structure, so let's just override this method.
     *
     * @todo: Differentiate between unauthorized and unauthenticated?
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        throw new UnauthorizedException();
    }

    /**
     * Don't ever display sensitive data in Whoops pages.
     *
     * @return void
     */
    protected function unsetSensitiveData()
    {
        foreach ($_ENV as $key => $value) {
            unset($_SERVER[$key]);
        }

        $_ENV = [];
    }
}
