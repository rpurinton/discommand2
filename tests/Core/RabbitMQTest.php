<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\Core\RabbitMQ;
use RPurinton\Discommand2\Core\Logger;
use React\EventLoop\LoopInterface;

class RabbitMQTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        $mock_options = [
            "host" => "localhost",
            "vhost" => "discommand2",
            "port" => 5672,
            "user" => "discommand",
            "password" => "discommand"
        ];
        $mock_loop = $this->createMock(LoopInterface::class);
        $mock_callback = function ($message) {
            return true;
        };
        $mock_logger = $this->createMock(Logger::class);
        $mock_logger->myName = 'testBrain';
        $mock_logger->method('log')->willReturn(true);
        $this->assertInstanceOf(
            RabbitMQ::class,
            new RabbitMQ($mock_options, $mock_loop, 'testBrain', $mock_callback, $mock_logger)
        );
    }
}
