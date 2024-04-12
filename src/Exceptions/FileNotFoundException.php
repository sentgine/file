<?php

namespace Sentgine\File\Exceptions;

use Exception;
use Throwable;

/**
 * Exception thrown when a file is not found.
 */
class FileNotFoundException extends Exception
{
    public function __construct(string $filename, string $message = "", int $code = 0, Throwable $previous = null)
    {
        if (empty($message)) {
            $message = "File ($filename) not found.";
        }
        parent::__construct($message, $code, $previous);
    }
}
