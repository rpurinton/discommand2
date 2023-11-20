<?php

namespace RPurinton\Discommand2\Exceptions;

class ConfigurationException extends \Exception
{
    public function __construct($message = null, $code = 0, \Throwable $previous = null)
    {
        if (!$message) $message = "Configuration error";
        parent::__construct($message, $code, $previous);
        die();
    }
}
