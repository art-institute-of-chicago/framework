<?php

namespace Aic\Hub\Core\Exceptions;

use Illuminate\Http\Response;

class TooManyIdsException extends AbstractException
{

    protected $message = 'Invalid number of ids';

    protected $detail = 'You have requested too many resources. Please set a smaller limit.';

    protected $code = Response::HTTP_FORBIDDEN;

}
