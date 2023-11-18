<?php

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\Logger;
use RPurinton\Discommand2\Exceptions\LogException;

class LogExceptionTest extends TestCase
{
    public function testLogException()
    {
        $this->expectException(LogException::class);
        $logger = new Logger('/invalid/log/dir');
        // Intentionally trigger a LogException
        $logger->log('Test message that should not be logged');
    }
}
