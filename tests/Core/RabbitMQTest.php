<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\Core\RabbitMQ;
use RPurinton\Discommand2\Core\Logger;
use React\EventLoop\Loop;

class RabbitMQTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        $options = [
            "host" => "localhost",
            "vhost" => "discommand2",
            "port" => 5672,
            "user" => "discommand",
            "password" => "discommand"
        ];
        $loop = Loop::get();
        $callback = function ($message) {
            return true;
        };
        $logger = new Logger('testBrain');
        $this->assertInstanceOf(
            RabbitMQ::class,
            new RabbitMQ($options, $loop, $callback, $logger)
        );
    }
}
