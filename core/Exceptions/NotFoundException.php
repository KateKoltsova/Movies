<?php

namespace Framework\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    public $message = 'Page is not found!';
    public $code = 404;
}