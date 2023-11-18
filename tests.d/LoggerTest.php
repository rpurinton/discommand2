<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\Logger;

class LoggerTest extends TestCase
{
    public function testLogCreation(): void
    {
        $logger = new Logger();
        $this->assertInstanceOf(Logger::class, $logger);
    }

    public function testLogMessage(): void
    {
        $logger = new Logger();
        $logMessage = 'Test log message';
        $logger->log($logMessage, 'INFO');
        $logFile = $logger->getLogDir() . '/' . date('Y-m-d') . '.log';
        $this->assertFileExists($logFile);
        $this->assertStringContainsString($logMessage, file_get_contents($logFile));
    }
}
