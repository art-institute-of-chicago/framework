<?php

namespace Aic\Hub\Core\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class AbstractException extends HttpException
{

    protected $message = null;

    protected $detail = null;

    protected $code = 500;

    public function __construct()
    {
        parent::__construct($this->code, $this->message);
    }

    /**
     * Return a more detailed message.
     *
     * @return string
     */
    public function getDetail()
    {
        return $this->detail;
    }

}
