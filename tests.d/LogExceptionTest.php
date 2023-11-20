<?php

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\Core\Logger;
use RPurinton\Discommand2\Exceptions\LogException;

class LogExceptionTest extends TestCase
{
    public function testLogException()
    {
        $this->expectException(LogException::class);
        $logger = new Logger('invalidBrain');
    }
}
