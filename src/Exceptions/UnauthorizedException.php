<?php

namespace Aic\Hub\Foundation\Exceptions;

use Illuminate\Http\Response;

class UnauthorizedException extends AbstractException
{

    protected $message = 'Unauthorized';

    protected $detail = 'You do not have access to this resource.';

    protected $code = Response::HTTP_UNAUTHORIZED;

}
