<?php

namespace RPurinton\Discommand2;


class GlobalExceptionHandler
{
    public function __construct(private $logger)
    {
    }

    public function handleException($exception)
    {
        $this->logger->log($exception->getMessage(), 'ERROR');
        // Signal error status for systemd
        if (trim(shell_exec('whoami') ?? "") === 'root') {
            // Use the exit code 1 to signal a general error
            // we shouldn't be running as root anyway
            exit(1);
        }
    }
}
