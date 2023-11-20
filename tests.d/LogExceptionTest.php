<?php

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\Core\Logger;
use RPurinton\Discommand2\Exceptions\ConfigurationException;

class LogExceptionTest extends TestCase
{
    public function testLogException()
    {
        $this->expectException(ConfigurationException::class);
        $logger = new Logger('invalidBrain');
    }
}
