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
        if ($this->logger && !$this->logger->log($exception->getMessage(), 'ERROR')) {
            echo ("Debug: GlobalExceptionHandler::handleException() failed to log exception " . get_class($exception) . " deatils: " . $exception->getMessage() . "\n");
        }
    }
}
