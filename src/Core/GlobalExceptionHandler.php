<?php

namespace RPurinton\Discommand2\Core;


class GlobalExceptionHandler
{
    public function __construct(private $logger)
    {
        set_exception_handler([$this, 'handleException']);
    }

    public function handleException(\Throwable $exception): void
    {
        echo ("Debug: GlobalExceptionHandler::handleException() called with " . get_class($exception) . " deatils: " . $exception->getMessage() . "\n");
        $this->logger->log($exception->getMessage(), 'ERROR');
        // Signal error status for systemd
        if (trim(shell_exec('whoami') ?? "") === 'root') {
            // Use the exit code 1 to signal a general error
            // we shouldn't be running as root anyway
            echo ("Exiting due to exeception as root.\n");
            exit(1);
        }
    }
}
