<?php

namespace Aigletter\Framework\Exceptions;

use Exception;

class MethodNotAllowedException extends Exception
{
    public $message = 'This method is not exists!';
    public $code = 405;
}