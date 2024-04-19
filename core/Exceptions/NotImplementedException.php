<?php

namespace Aigletter\Framework\Exceptions;

use Exception;

class NotImplementedException extends Exception
{
    public $message = 'This controller is not implemented!';
    public $code = 501;
}