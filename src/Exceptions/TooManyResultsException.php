<?php

namespace Aic\Hub\Foundation\Exceptions;

use Illuminate\Http\Response;

class TooManyResultsException extends AbstractException
{
    protected $message = 'Invalid number of results';

    protected $detail = 'You have requested too many results. Please refine your parameters.';

    protected $code = Response::HTTP_FORBIDDEN;
}
