<?php

namespace Aic\Hub\Foundation\Exceptions;

use Illuminate\Http\Response;

class ItemNotFoundException extends AbstractException
{

    protected $message = 'Not found';

    protected $detail = 'The item you requested cannot be found.';

    protected $code = Response::HTTP_NOT_FOUND;

}
