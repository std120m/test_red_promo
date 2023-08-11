<?php

namespace App\Exceptions;

use Exception;

class BookIsAlreadyReserwedException extends Exception
{
    public function __construct($message = 'BOOK_IS_ALREADY_RESERVED', $val = 0, Exception $old = null) {
        parent::__construct($message, $val, $old);
    }
}
