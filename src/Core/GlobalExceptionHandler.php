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
        if (!$this->logger) {
            echo (($exception->getMessage() . "\n"));
        } else {
            !$this->logger->log($exception->getMessage(), 'ERROR');
        }
    }
}
