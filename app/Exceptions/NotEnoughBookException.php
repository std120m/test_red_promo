<?php

namespace App\Exceptions;

use Exception;

class NotEnoughBookException extends Exception
{
    public function __construct($message = 'NOT_ENOUGH_BOOKS', $val = 0, Exception $old = null) {
        parent::__construct($message, $val, $old);
    }
}
