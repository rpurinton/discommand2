<?php

namespace RPurinton\Discommand2\Core;

use RPurinton\Discommand2\Core\Logger;
use RPurinton\Discommand2\Exceptions\FatalException;

class GlobalExceptionHandler extends Logger
{
    public function __construct(string $myName)
    {
        parent::__construct($myName);
        set_exception_handler($this->handleException(...));
    }

    public function handleException(\Throwable $exception): void
    {
        $this->log($exception->getMessage(), "ERROR") or throw new FatalException("Failed to log");
        if ($exception instanceof FatalException) exit(1);
    }
}
