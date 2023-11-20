<?php

declare(strict_types=0);

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
        $logger = new Logger('testBrain');
        $rabbitmq = new RabbitMQ(
            $options,
            $loop,
            function ($message) {
            },
            $logger
        );
        $this->assertInstanceOf(RabbitMQ::class, $rabbitmq);
    }
}
