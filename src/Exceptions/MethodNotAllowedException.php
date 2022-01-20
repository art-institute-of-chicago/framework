<?php

namespace Aic\Hub\Foundation\Exceptions;

use Illuminate\Http\Response;

class MethodNotAllowedException extends AbstractException
{
    protected $message = 'Method not allowed';

    protected $detail = 'This HTTP method is not allowed.';

    protected $code = Response::HTTP_METHOD_NOT_ALLOWED;
}
