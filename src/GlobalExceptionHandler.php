<?php

namespace RPurinton\Discommand2;

class GlobalExceptionHandler
{
    public static function handleException($exception)
    {
        // Log the exception details
        $logger = new Logger();
        $logger->log($exception->getMessage(), 'ERROR');

        // Signal error status for systemd
        if (php_sapi_name() === 'cli') {
            // Use the exit code 1 to signal a general error
            exit(1);
        }
    }
}

// Register the global exception handler
set_exception_handler([GlobalExceptionHandler::class, 'handleException']);
