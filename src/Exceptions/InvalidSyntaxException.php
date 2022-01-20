<?php

namespace Aic\Hub\Foundation\Exceptions;

use Illuminate\Http\Response;

class InvalidSyntaxException extends AbstractException
{
    protected $message = 'Invalid syntax';

    protected $detail = 'The identifier syntax is invalid.';

    protected $code = Response::HTTP_BAD_REQUEST;
}
