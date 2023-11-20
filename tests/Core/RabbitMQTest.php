<?php

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\Core\RabbitMQ;
use RPurinton\Discommand2\Core\Logger;
use React\EventLoop\Loop;

class RabbitMQTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        $options = json_decode(file_get_contents(__DIR__ . '/../../configs/bunny.json'), true);
        $loop = Loop::get();
        $callback = function ($msg) {
            // do nothing
        };
        $logger = new Logger('testBrain');
        $this->assertInstanceOf(
            RabbitMQ::class,
            new RabbitMQ($options, $loop, $callback, $logger)
        );
    }
}
