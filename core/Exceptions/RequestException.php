<?php

namespace Aigletter\Framework\Exceptions;

use Exception;

class RequestException extends Exception
{
    public $message = 'Request without parameters!';
    public $code = 400;
}