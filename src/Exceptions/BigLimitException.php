<?php

namespace Aic\Hub\Foundation\Exceptions;

use Illuminate\Http\Response;

class BigLimitException extends AbstractException
{
    protected $message = 'Invalid limit';

    protected $detail = 'You have requested too many resources per page. Please set a smaller limit.';

    protected $code = Response::HTTP_FORBIDDEN;
}
