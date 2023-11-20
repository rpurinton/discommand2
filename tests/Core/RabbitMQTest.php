<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\Core\RabbitMQ;

class RabbitMQTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            RabbitMQ::class,
            new RabbitMQ(['host' => 'localhost'], new React\EventLoop\StreamSelectLoop(), 'test_queue', function() {}, new Brain('test'))
        );
    }
}
