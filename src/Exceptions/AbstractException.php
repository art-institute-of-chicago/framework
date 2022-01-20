<?php

namespace Aic\Hub\Foundation\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class AbstractException extends HttpException
{
    protected $message;

    protected $detail;

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
