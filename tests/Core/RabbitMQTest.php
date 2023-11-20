<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\Core\RabbitMQ;
use RPurinton\Discommand2\Core\Brain;

class RabbitMQTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        $mock_callback = function ($message) {
            return true;
        };
        $mock_brain = $this->createMock(Brain::class);
        $mock_brain->myName = 'testBrain';
        $this->assertInstanceOf(
            RabbitMQ::class,
            new RabbitMQ(['host' => 'localhost'], new React\EventLoop\StreamSelectLoop(), 'test_queue', $mock_callback, $mock_brain)
        );
    }
}
