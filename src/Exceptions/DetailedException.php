<?php

namespace Aic\Hub\Foundation\Exceptions;

class DetailedException extends AbstractException
{

    /**
     * Throw this when you want to display a custom error in the API.
     * Making a new exception class is preferable. Use as fallback.
     */
    public function __construct( $message, $detail, $code = 500 )
    {

        $this->message = $message;
        $this->detail = $detail;
        $this->code = $code;

        parent::__construct();
    }

}
